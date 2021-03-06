<?php

declare(strict_types=1);

namespace IsaevDimka\RussianPost;

class AddressList
{
    private $stack = []; // Список адресов для нормализации

    private $idList = []; // Список id, которые уже есть в стэке

    public function add($address, $id = false)
    {
        if (empty($id)) {
            do {
                $id = count($this->stack);
            } while (isset($this->idList[$id]));
        } else {
            if (isset($this->idList[$id])) {
                throw new \InvalidArgumentException('ID адреса должен быть уникальным');
            }
        }

        $info['id'] = $id;
        $info['original-address'] = $address;
        $this->stack[] = $info;
        $this->idList[$id] = true;
    }

    public function get()
    {
        if (empty($this->stack)) {
            throw new \InvalidArgumentException('Список адресов пуст');
        }

        return $this->stack;
    }
}
