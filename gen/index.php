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
	
	$site_title = "Gen blog template";
	
	$url = Url::fromString( $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
	
	$category = urldecode($url->getSegment(2));
	$posting = $url->getSegment(3);
	
	$dsn = "sqlite:" .getcwd(). "/database/db.sqlite";
	try {
		$database = new SimpleCrud(new PDO($dsn));
	} catch (PDOException $e) {
		echo "Connection to '$dsn' failed. Reason: " . $e->getMessage();
	}
	
	function ClearDB(){
		global $base_path;
		global $database;
		$files = Dir::scan( getcwd() . '/' . content_path,
			[
				'include' => '*.txt',
				'exclude' => '*cdma.txt',
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
		
		$database->execute("DROP TABLE IF EXISTS post");
		$database->execute("DROP TABLE IF EXISTS category");
		//~ $database->execute("DROP TABLE IF EXISTS sub_category");
		$database->execute(
<<<EOT
CREATE TABLE `category` (
	`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT
);
EOT
		);
		$database->execute(
<<<EOT
CREATE TABLE "post" (
	`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`category_id`	INTEGER,
	`title`	TEXT,
	`body`	TEXT,
	`picture`	TEXT,
	`url`	TEXT,
	`waktu`	INTEGER
);
EOT
		);
		$database->execute(
<<<EOT
CREATE TABLE IF NOT EXISTS `sub_category` (
	`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`category_id`	INTEGER,
	`name`	TEXT
);
EOT
		);
		
		return $files;
	}
	
	function GenerateContent(){
		global $base_path;
		global $database;
		
		$files = ClearDB();
		
		//~ clear all database
		//~ $database->category->delete()->run();
		//~ $database->post->delete()->run();
		
		echo "Generate content ... <br/>";
		
		foreach($files as $fi){
			echo $fi . "<br/>";
			$tcate = str_replace($base_path . '/', '', $fi);
			$tcate = explode('/', $tcate);
			assert(count($tcate)==2);
			$tcat = $tcate[0];
			$tpost = str_replace('.txt', '', $tcate[1]);
			$sel_category = $database->category->select()
				->where('name=:category', [':category' => $tcat])
				->one()
				->run();
			if(empty($sel_category)){
				$cat_id = $database->category->insert()
					->data([
						'name'=>$tcat
					])->run();
			}else
				$cat_id = $sel_category->id;
			//~ read file
			$fn = new TextFile($fi);
			$tl = $fn->countLines();
			$title = $fn->getLineContent(0);
			$uri = str_replace(' ', '-', $title);
			$content = '';
			$picture = array();
			for($i=1; $i<$tl; $i++){
				$rowc = $fn->getLineContent($i);
				if($rowc[0]=='#'){
					$npic = substr($rowc, 1);
					$picture[] = $tcat .'/'. $npic;
					$content .= "<<<$tcat/$npic>>>";
				}else{
					$content .= "<p>$rowc</p>";
				}
			}
			//~ insert post
			$database->post->insert()
				->data([
					'category_id'=>$cat_id,
					'title'=>$title,
					'body'=>$content,
					'picture'=>implode(',', $picture),
					'url'=>$uri,
					'waktu'=>time()
				])->run();
		}
		
		$fi = getcwd() . '/content/cdma.txt';
		
		echo "Read CDMA $fi ... <br/>";
		
		$fn = new TextFile($fi);
		$title = $fn->getLineContent(0);
		$cat_id = $database->category->insert()->data(['name'=>$title])->run();
		$content = '';
		$picture = array();
		for($i=1; $i<$tl; $i++){
			$rowc = $fn->getLineContent($i);
			if($rowc[0]=='#'){
				$npic = substr($rowc, 1);
				$picture[] = $npic;
				$content .= "<<<$npic>>>";
			}else{
				$content .= "<p>$rowc</p>";
			}
		}
		$uri = str_replace(' ', '-', $title);
		$database->post->insert()
			->data([
				'category_id'=>$cat_id,
				'title'=>$title,
				'body'=>$content,
				'picture'=>implode(',', $picture),
				'url'=>$uri,
				'waktu'=>time()
		])->run();
		
		echo "done ... <br/>";
		
		echo "<a href='" .base_url. "'>Click here</a>";
		
		die;
	}
	
	function InputContent(){
		echo '<html>';
		echo '<head><title>Input Post Title</title>';
		echo '</head>';
		echo '<body>';
?>
	<table>
		<thead>
			<tr>
				<td>No</td>
				<td>Kategori</td>
			</tr>
		</thead>
	</table>
<?php
		echo '</body>';
		echo '</html>';
		die;
	}
	
				
	if($category=="gen"){
		GenerateContent();
	}if($category=="gon"){
		InputContent();
	}if($category=="sitemap.xml"){
		include_once "sitemap.php";
	}else{
		$all_category = $database->category->select()
				->run();
				
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
		
		if(count($the_posts)==1){
			foreach($the_posts as $po){
				$the_post = $po;
			}
		}
	}
	
	include "template.php";
	
