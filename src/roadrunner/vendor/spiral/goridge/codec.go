package goridge

import (
	"errors"
	"io"
	"net/rpc"
	"reflect"

	json "github.com/json-iterator/go"
)

// Codec represent net/rpc bridge over Goridge socket relay.
type Codec struct {
	relay  Relay
	closed bool
}

// NewCodec initiates new server rpc codec over socket connection.
func NewCodec(rwc io.ReadWriteCloser) *Codec {
	return &Codec{relay: NewSocketRelay(rwc)}
}

// NewCodecWithRelay initiates new server rpc codec with a relay of choice.
func NewCodecWithRelay(relay Relay) *Codec {
	return &Codec{relay: relay}
}

// ReadRequestHeader receives
func (c *Codec) ReadRequestHeader(r *rpc.Request) error {
	data, p, err := c.relay.Receive()
	if err != nil {
		return err
	}

	if !p.HasFlag(PayloadControl) {
		return errors.New("invalid rpc header, control flag is missing")
	}

	if !p.HasFlag(PayloadRaw) {
		return errors.New("rpc response header must be in {rawData}")
	}

	if !p.HasPayload() {
		return errors.New("rpc request header can't be empty")
	}

	return unpack(data, &r.ServiceMethod, &r.Seq)
}

// ReadRequestBody fetches prefixed body data and automatically unmarshal it as json. RawBody flag will populate
// []byte lice argument for rpc method.
func (c *Codec) ReadRequestBody(out interface{}) error {
	data, p, err := c.relay.Receive()
	if err != nil {
		return err
	}

	if out == nil {
		// discarding
		return nil
	}

	if !p.HasPayload() {
		return nil
	}

	if p.HasFlag(PayloadRaw) {
		if bin, ok := out.(*[]byte); ok {
			*bin = append(*bin, data...)
			return nil
		}

		return errors.New("{rawData} request for " + reflect.ValueOf(out).String())
	}

	return json.Unmarshal(data, out)
}

// WriteResponse marshals response, byte slice or error to remote party.
func (c *Codec) WriteResponse(r *rpc.Response, body interface{}) error {
	data := make([]byte, len(r.ServiceMethod)+Uint64Size)
	pack(r.ServiceMethod, r.Seq, data)
	if err := c.relay.Send(data, PayloadControl|PayloadRaw); err != nil {
		return err
	}

	if r.Error != "" {
		return c.relay.Send([]byte(r.Error), PayloadError|PayloadRaw)
	}

	switch bin := body.(type) {
	case *[]byte:
		return c.relay.Send(*bin, PayloadRaw)
	case []byte:
		return c.relay.Send(bin, PayloadRaw)
	}

	packed, err := json.Marshal(body)
	if err != nil {
		return err
	}

	return c.relay.Send(packed, 0)
}

// Close underlying socket.
func (c *Codec) Close() error {
	if c.closed {
		return nil
	}

	c.closed = true
	return c.relay.Close()
}
