<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    
    public function parent()
    {
    	return $this->belongsTo('App\Category', 'category_parent_id');
    }
    
    public function children()
    {
    	return $this->hasMany('App\Category', 'category_parent_id');
    }
    
    public function getAllHierarhy($_parent_id = null, $_level = 0)
    {
    	$ret = array();
    	$_level++;
    	$categoryCollection = $this->where('category_parent_id', $_parent_id)
    							->where('category_active', '=', 1)
    							->with('children')
    							->orderBy('category_ord', 'asc')
    							->get();
    	
    	if(!empty($categoryCollection)){
    		foreach ($categoryCollection as $k => $v){
    			$ret[$v->category_id] = array('cid' => $v->category_id, 'title' => $v->category_title, 'level' => $_level);
    			if($v->children->count() > 0){
    				$ret[$v->category_id]['c'] = $this->getAllHierarhy($v->category_id, $_level);
    			}
    		}
    	}
    	return $ret;
    }
    
    public function getOneLevel($_parent_id = null)
    {
    	return $this->where('category_parent_id', $_parent_id)
    				->orderBy('category_ord', 'asc')
    				->get();
    }
}