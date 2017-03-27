<?php
// +----------------------------------------------------------------------
// | 红薯网 [ Book模块下fensi模型，主要与用户相关的数据操作在这个类里实现 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2015 http://www.hongshu.com All rights reserved.
// +----------------------------------------------------------------------
// | Version: v2.0
// +----------------------------------------------------------------------
// | Author: jiachao <jiachao@hongshu.com>
// +----------------------------------------------------------------------
// | Date: 
// +----------------------------------------------------------------------
// | Last Modified by: jiachao
// +----------------------------------------------------------------------
// | Last Modified time: 
// +----------------------------------------------------------------------

namespace HS;

class PriModel{
   /**
		* 界定用户权限是否在允许权限中
		* @param	$userPopedom	用户权限
		* @param	$allowedPopedom	允许的权限
		* @return	boolean			用户权限在被允许的权限中返回true
		*/
		
		function allowedPopedom ( $userPopedom, $allowedPopedom )
		{
			if( !is_array( $userPopedom ) )
			{
				$userPopedom = explode( ";", $userPopedom );
			}
			
			if( !is_array( $allowedPopedom ) )
			{
				$allowedPopedom = explode( ";", $allowedPopedom );
			}
			
			
			$allowedPopedom = $this->getPopedomAggregate( $allowedPopedom );
			return $this->poundary( $userPopedom, $allowedPopedom );
				
		}
		
		
		/**
		* 界定用户权限是否在排除权限中
		* @param	$userPopedom	用户权限
		* @param	$deniedPopedom	允许的权限
		* @return	boolean			用户权限在被排除的权限中返回true
		*/
		
		function deniedPopedom ( $userPopedom, $deniedPopedom )
		{
			if( !is_array( $userPopedom ) )
			{
				$userPopedom = explode( ";", $userPopedom );
			}
			
			if( !is_array( $deniedPopedom ) )
			{
				$deniedPopedom = explode( ";", $deniedPopedom );
			}
			
			$userPopedom = $this->getPopedomAggregate( $userPopedom );
			return $this->poundary( $userPopedom, $deniedPopedom );
			
		}
		

		/**
		* 界定用户权限是否在权限组中
		* @param	$userPopedom	用户权限
		* @param	$popedom		权限组
		* @return	boolean			用户权限在被排除的权限中返回true
		*/

		function poundary ( $userPopedom, $popedom )
		{
			
			$intersect = array_intersect ($userPopedom, $popedom);
			
			if( count( $intersect ) > 0 )
			{
				return true;
			}
			else{
				return false;
			}
		}
		
		
		/**
		* 得到完整的权限集合
		* @param	$popedom		权限组
		* @return	array			权限集合
		*/
		
		function getPopedomAggregate ( $popedom )
		{
			$temp;
			$aggregate = array();
			
			for( $i = 0; $i < sizeof( $popedom ); $i++ )
			{
				array_push ( $aggregate, $popedom[$i] );
				$length = strlen( $popedom[$i] ) / 2 - 1;
				$temp = $popedom[$i];
				
				for( $j = 0; $j < $length ; $j++ )
				{
					$temp = substr( $temp, 0, strlen( $temp ) - 2 );
					array_push ( $aggregate, $temp );
				}
			}
			
			array_unique( $aggregate );
			
			return $aggregate;
		}
	
}