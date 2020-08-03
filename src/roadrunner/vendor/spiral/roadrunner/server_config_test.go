package roadrunner

import (
	"github.com/stretchr/testify/assert"
	"testing"
	"time"
)

func Test_ServerConfig_PipeFactory(t *testing.T) {
	cfg := &ServerConfig{Relay: "pipes"}
	f, err := cfg.makeFactory()

	assert.NoError(t, err)
	assert.IsType(t, &PipeFactory{}, f)

	cfg = &ServerConfig{Relay: "pipe"}
	f, err = cfg.makeFactory()
	assert.NoError(t, err)
	assert.NotNil(t, f)
	defer func() {
		err := f.Close()
		if err != nil {
			t.Errorf("error closing factory or underlying connections: error %v", err)
		}
	}()

	assert.NoError(t, err)
	assert.IsType(t, &PipeFactory{}, f)
}

func Test_ServerConfig_SocketFactory(t *testing.T) {
	cfg := &ServerConfig{Relay: "tcp://:9111"}
	f1, err := cfg.makeFactory()
	assert.NoError(t, err)
	assert.NotNil(t, f1)
	defer func() {
		err := f1.Close()

		if err != nil {
			t.Errorf("error closing factory or underlying connections: error %v", err)
		}
	}()

	assert.NoError(t, err)
	assert.IsType(t, &SocketFactory{}, f1)
	assert.Equal(t, "tcp", f1.(*SocketFactory).ls.Addr().Network())
	assert.Equal(t, "[::]:9111", f1.(*SocketFactory).ls.Addr().String())

	cfg = &ServerConfig{Relay: "tcp://localhost:9112"}
	f, err := cfg.makeFactory()
	assert.NoError(t, err)
	assert.NotNil(t, f)
	defer func() {
		err := f.Close()
		if err != nil {
			t.Errorf("error closing factory or underlying connections: error %v", err)
		}
	}()

	assert.NoError(t, err)
	assert.IsType(t, &SocketFactory{}, f)
	assert.Equal(t, "tcp", f.(*SocketFactory).ls.Addr().Network())
	assert.Equal(t, "127.0.0.1:9112", f.(*SocketFactory).ls.Addr().String())
}

func Test_ServerConfig_UnixSocketFactory(t *testing.T) {
	cfg := &ServerConfig{Relay: "unix://unix.sock"}
	f, err := cfg.makeFactory()
	if err != nil {
		t.Error(err)
	}

	defer func() {
		err := f.Close()
		if err != nil {
			t.Errorf("error closing factory or underlying connections: error %v", err)
		}
	}()

	assert.NoError(t, err)
	assert.IsType(t, &SocketFactory{}, f)
	assert.Equal(t, "unix", f.(*SocketFactory).ls.Addr().Network())
	assert.Equal(t, "unix.sock", f.(*SocketFactory).ls.Addr().String())
}

func Test_ServerConfig_ErrorFactory(t *testing.T) {
	cfg := &ServerConfig{Relay: "uni:unix.sock"}
	f, err := cfg.makeFactory()
	assert.Nil(t, f)
	assert.Error(t, err)
	assert.Equal(t, "invalid relay DSN (pipes, tcp://:6001, unix://rr.sock)", err.Error())
}

func Test_ServerConfig_ErrorMethod(t *testing.T) {
	cfg := &ServerConfig{Relay: "xinu://unix.sock"}

	f, err := cfg.makeFactory()
	assert.Nil(t, f)
	assert.Error(t, err)
}

func Test_ServerConfig_Cmd(t *testing.T) {
	cfg := &ServerConfig{
		Command: "php tests/client.php pipes",
	}

	cmd := cfg.makeCommand()
	assert.NotNil(t, cmd)
}

func Test_ServerConfig_SetEnv(t *testing.T) {
	cfg := &ServerConfig{
		Command: "php tests/client.php pipes",
		Relay:   "pipes",
	}

	cfg.SetEnv("key", "value")

	cmd := cfg.makeCommand()
	assert.NotNil(t, cmd)

	c := cmd()

	assert.Contains(t, c.Env, "KEY=value")
	assert.Contains(t, c.Env, "RR_RELAY=pipes")
}

func Test_ServerConfig_SetEnv_Relay(t *testing.T) {
	cfg := &ServerConfig{
		Command: "php tests/client.php pipes",
		Relay:   "unix://rr.sock",
	}

	cfg.SetEnv("key", "value")

	cmd := cfg.makeCommand()
	assert.NotNil(t, cmd)

	c := cmd()

	assert.Contains(t, c.Env, "KEY=value")
	assert.Contains(t, c.Env, "RR_RELAY=unix://rr.sock")
}

func Test_ServerConfigDefaults(t *testing.T) {
	cfg := &ServerConfig{
		Command: "php tests/client.php pipes",
	}

	err := cfg.InitDefaults()
	if err != nil {
		t.Errorf("error during the InitDefaults: error %v", err)
	}

	assert.Equal(t, "pipes", cfg.Relay)
	assert.Equal(t, time.Minute, cfg.Pool.AllocateTimeout)
	assert.Equal(t, time.Minute, cfg.Pool.DestroyTimeout)
}

func Test_Config_Upscale(t *testing.T) {
	cfg := &ServerConfig{
		Command:      "php tests/client.php pipes",
		RelayTimeout: 1,
		Pool: &Config{
			AllocateTimeout: 1,
			DestroyTimeout:  1,
		},
	}

	cfg.UpscaleDurations()
	assert.Equal(t, time.Second, cfg.RelayTimeout)
	assert.Equal(t, time.Second, cfg.Pool.AllocateTimeout)
	assert.Equal(t, time.Second, cfg.Pool.DestroyTimeout)
}
