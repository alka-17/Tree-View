<?php

namespace App\Http\Services\Api;

use App\Helpers\Response;
use App\Models\TreeEntry;
use App\Traits\MagicConstants;

class TreeService
{
  use MagicConstants;
  
  /**
   * method to get tree entry data
   * @param $request
   * @return json
   */
  public function getData()
  {
    try {
      $data = TreeEntry::join('tree_entry_lang as tl', 'tl.entry_id', 'tree_entry.entry_id')->get();
      //return $data;
      if(empty($data)){
        return Response::success($data, trans('message.DATA_NOT_FOUND'), SUCCESS);
      }
      return Response::success($data, trans('message.DATA_FOUND'), SUCCESS);

    } catch (\Throwable $e) {
      return Response::error('', $e->getMessage(), UNAUTHORIZED);

    }
  }
  
}