<?php

namespace App\Util;

class FormataJsTree
{

	public function ajustaCollection( $collection ){
		
		 $collection = $collection->map(function ($item, $key) {
            $item->text = $item->nome;
            $item->selected = false;
            $item->opened = false;
            $item->icon = 'fa fa-eye';
            return $item;
        });

        return $collection;

    }



}