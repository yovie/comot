<?php
	require __DIR__ . '/vendor/autoload.php';
	
	
	const content_path	=	"content";
	$base_path = getcwd() . '/' . content_path;
	const table_category = "category";
	const table_post = "post";
	const base_url = "http://localhost/gen/";

	use Spatie\Url\Url;
	use Lead\Dir\Dir;	
	use SimpleCrud\SimpleCrud;
	use TextFile\TextFile;
	
	$url = Url::fromString( $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
	
	$category = $url->getSegment(2);
	$posting = $url->getSegment(3);
	
	$dsn = "sqlite:" .getcwd(). "/database/db.sqlite";
	try {
		$database = new SimpleCrud(new PDO($dsn));
	} catch (PDOException $e) {
		echo "Connection to '$dsn' failed. Reason: " . $e->getMessage();
	}
	
	function GenerateContent(){
		global $base_path;
		global $database;
		$files = Dir::scan( getcwd() . '/' . content_path,
			[
				'include' => '*.txt',
				//~ 'exclude' => '*.save.txt',
				'type'    => 'file',
				'skipDots'       => true,
				'leavesOnly'     => true,
				'followSymlinks' => true,
				'recursive'      => true,
				'copyHandler'    => function($path, $target) {
					copy($path, $target);
				}
			]
		);
		
		//~ clear all database
		$database->category->delete()->run();
		$database->post->delete()->run();
		
		foreach($files as $fi){
			$tcate = str_replace($base_path . '/', '', $fi);
			$tcate = explode('/', $tcate);
			assert(count($tcate)==2);
			$tcat = $tcate[0];
			$tpost = str_replace('.txt', '', $tcate[1]);
			$sel_category = $database->category->select()
				->where('category=:category', [':category' => $tcat])
				->one()
				->run();
			if(empty($sel_category)){
				$cat_id = $database->category->insert()
					->data([
						'category'=>$tcat
					])->run();
			}else
				$cat_id = $sel_category->id;
			//~ read file
			$fn = new TextFile($fi);
			$tl = $fn->countLines();
			$picture = $fn->getLineContent(0);
			$title = $fn->getLineContent(1);
			$content = '';
			for($i=2; $i<$tl; $i++){
				$content .= $fn->getLineContent($i);
			}
			//~ insert post
			$database->post->insert()
				->data([
					'category_id'=>$cat_id,
					'title'=>$title,
					'body'=>$content,
					'picture'=>$picture,
					'url'=>$tpost
				])->run();
		}
	}
	
	$all_category = $database->category->select()
				->run();
				
	if($category=="gen"){
		GenerateContent();
	}else{
		$the_posts = $database->post->select()
				->orderBy('post.id ASC')
				->run();
		$the_posts = array_map(function($itm){
				global $database;
				$category = $database->category
					->select()
					->relatedWith($itm)
					->one()
					->run();
				return (object)array(
					'id'=>$itm->id,
					'title'=>$itm->title,
					'body'=>$itm->body,
					'url'=>$itm->url,
					'picture'=>$itm->picture,
					'category'=>$category->name
				);
			}, iterator_to_array($the_posts));
		$post_keys = array_map(function($itm){
				return $itm->id;
			}, $the_posts);
		while(count($post_keys)>10){
			$rid = array_rand($post_keys);
			array_splice($the_posts, $rid, 1);
			array_splice($post_keys, $rid, 1);
		}
		
		if(!empty($category)){
			$the_category = $database->category->select()
				->where('name=:category', [':category' => $category])
				->one()
				->run();
		}
		
		if(!empty($the_category)){
			$the_posts = $database->post->select()
				->where('category_id=:category_id', [':category_id' => $the_category->id])
				->orderBy('post.id ASC')
				->run();
			$the_posts = array_map(function($itm){
					global $database;
					$category = $database->category
						->select()
						->relatedWith($itm)
						->one()
						->run();
					return (object)array(
						'id'=>$itm->id,
						'title'=>$itm->title,
						'body'=>$itm->body,
						'url'=>$itm->url,
						'picture'=>$itm->picture,
						'category'=>$category->name
					);
				}, iterator_to_array($the_posts));
			$post_keys = array_map(function($itm){
					return $itm->id;
				}, $the_posts);
			while(count($post_keys)>10){
				$rid = array_rand($post_keys);
				array_splice($the_posts, $rid, 1);
				array_splice($post_keys, $rid, 1);
			}
		}
		
		if(!empty($posting)){
			if(!empty($the_category)){
				$the_post = $database->post->select()
					->where('category_id=:catid', [':catid' => $the_category->id])
					->where('url=:url', [':url' => $posting])
					->one()
					->run();
			}
		}
		
	}
	
	include "template.php";
	
