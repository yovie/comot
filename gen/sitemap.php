<?php
	$all_posts = $database->post->select()
				->orderBy('post.id ASC')
				->run();
	$all_posts = array_map(function($itm){
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
					'waktu'=>$itm->waktu,
					'category'=>$category->name
				);
			}, iterator_to_array($all_posts));

?><?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

	<?php foreach($all_posts as $ap): ?>
	<url>

      <loc><?php echo base_url . $ap->category .'/'. $ap->url ?></loc>

      <lastmod><?php echo date('Y-m-d', $ap->waktu); ?></lastmod>

      <changefreq>daily</changefreq>

      <priority>0.8</priority>

	</url>
	<?php endforeach; ?>

</urlset> 


<?php die; ?>
