<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\TreeService;

class TreeController extends Controller
{
  protected $treeService;

  /**
   * constructor method to create Object
   * @return object
   */
  public function __construct(TreeService $treeService)
  {
    $this->treeService = $treeService;
  }

  /**
   * method to get tree entry data
   * @param $request
   * @return json
   */
  public function getTreeData()
  {
    return $this->treeService->getData();
  }
}
