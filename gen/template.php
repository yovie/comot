<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $site_title ?></title>
    <link href="<?php echo base_url ?>assets/css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url ?>assets/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url ?>assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.6/css/jquery.fancybox.min.css" rel='stylesheet' type='text/css' />
	<script src="<?php echo base_url ?>assets/js/jquery-1.10.2.js"></script>
</head>
<body>
    <div class="navbar navbar-inverse set-radius-zero" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url; ?>">

                    <img src="<?php echo base_url ?>assets/img/logo.png" />
                </a>

            </div>

            <div class="right-div">
                
            </div>
        </div>
    </div>
    
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <?php foreach($all_category as $ac): ?>
                            <li><a href="<?php echo base_url . $ac->name ?>"><?php echo ucfirst($ac->name) ?></a></li>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
     
    <div class="content-wrapper">
         <div class="container">
                          
             <div class="row">

              <div class="col-md-8 col-sm-8 col-xs-12">
				 
				 <?php if(!isset($the_post)): ?> 
                 <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel" >
                   
                    <div class="carousel-inner">
                        <?php $nn = 0; foreach($the_posts as $pp): 
							$ppos = explode(',', $pp->picture);
							foreach($ppos as $ipos):
								if(empty($ipos))
									continue;
                        ?>
                        <div class="item <?php echo ($nn==0)?'active':''?> ">

                            <img src="<?php echo base_url . 'content/' . $pp->category . '/' . $ipos ?>" alt="<?php echo $pp->title ?>" />
                           
                        </div>
                        <?php 		$nn++; 
								endforeach;
							endforeach; 
						?>
                    </div>
                    
                     <ol class="carousel-indicators">
						<?php $mm = 0; foreach($the_posts as $pp) :
							$sa = explode(',',$pp->picture);
							foreach($sa as $ss):
						?>
							<li data-target="#carousel-example" data-slide-to="<?php echo $mm ?>" class="<?php echo ($mm==0)?'active':''?>"></li>
                        <?php $mm++; 
								endforeach;
							endforeach; 
						?>
                    </ol>
                    
                     <a class="left carousel-control" href="#carousel-example" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					  </a>
					  <a class="right carousel-control" href="#carousel-example" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					  </a>
                </div>
                
                <br/>
                
                <p style="text-align: center;">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" data-mce-target="_blank">
						<img src="assets/img/fb.png" alt="social-icons-02-1" width="70px">
					</a>&nbsp;&nbsp;
					<a href="https://twitter.com/home?status=<?php echo $site_title .', open : '. $url; ?>" target="_blank" data-mce-target="_blank">
						<img src="assets/img/tw.png" alt="social-icons-01-2" width="70px">
					</a>&nbsp;&nbsp;
					<a href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" data-mce-target="_blank">
						<img src="assets/img/gplus.png" alt="social-icons-05" width="70px">
					</a>&nbsp;&nbsp;
					<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $site_title; ?>&amp;summary=<?php echo $site_title; ?>&amp;source=" target="_blank" data-mce-target="_blank">
						<img src="assets/img/lin.png" alt="social-icons-03-1" width="70px">
					</a>&nbsp;&nbsp;
					<a href="https://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;media=<?php echo $url; ?>&amp;description=<?php echo $site_title; ?>" target="_blank" data-mce-target="_blank">
						<img src="assets/img/pth.png" alt="social-icons-04" width="70px">
					</a>
				</p>
				
                <?php endif; ?>
                
                <div class="content">
					
					<?php if(isset($the_post)): ?>
						<h1><?php echo $the_post->title ?></h1>
						<?php 
							$bodies = $the_post->body;
							
							if(!empty($the_post->picture)): 
								$tpic = explode(',', $the_post->picture);
								foreach($tpic as $ppic):
									if(empty($ppic))
										continue;
									$puri = base_url . 'content/' . $the_category->name . '/' . $ppic;
									$tosearch = '<a id="picpop" href="' .$puri. '" title="' .$the_post->title. '">'
												. '<img src="' .$puri. '"  width="70%" alt="' .$the_post->title. '" />'
												. '</a>';												
									$bodies = str_replace("<<<$ppic>>>", $tosearch, $bodies); 
								endforeach;
							endif; 
							
							echo $bodies;
						?>
						
						<p style="text-align: center;">
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" data-mce-target="_blank">
								<img src="<?php echo base_url ?>assets/img/fb.png" alt="social-icons-02-1" width="70px">
							</a>&nbsp;&nbsp;
							<a href="https://twitter.com/home?status=<?php echo $the_post->title .', open : '. $url; ?>" target="_blank" data-mce-target="_blank">
								<img src="<?php echo base_url ?>assets/img/tw.png" alt="social-icons-01-2" width="70px">
							</a>&nbsp;&nbsp;
							<a href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" data-mce-target="_blank">
								<img src="<?php echo base_url ?>assets/img/gplus.png" alt="social-icons-05" width="70px">
							</a>&nbsp;&nbsp;
							<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $the_post->title; ?>&amp;summary=<?php echo $the_post->title; ?>&amp;source=" target="_blank" data-mce-target="_blank">
								<img src="<?php echo base_url ?>assets/img/lin.png" alt="social-icons-03-1" width="70px">
							</a>&nbsp;&nbsp;
							<a href="https://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;media=<?php echo $url; ?>&amp;description=<?php echo $the_post->title; ?>" target="_blank" data-mce-target="_blank">
								<img src="<?php echo base_url ?>assets/img/pth.png" alt="social-icons-04" width="70px">
							</a>
						</p>
						
					<?php endif; ?>
					
                </div>
              </div>
              
                 
                 <div class="col-md-4 col-sm-4 col-xs-12">
                 
                          <div class="panel panel-default">
							<div class="panel-heading">
								Other Posts
							</div>
							<div class="panel-body">
								<?php foreach($the_posts as $posts): ?>
								<p>
									<a href="<?php echo base_url . $posts->category .'/'. $posts->url; ?>"><?php echo $posts->title ?></a>
								</p>
								<?php endforeach; ?>
							</div>
                        </div>
                
					
				</div>
             
                 </div>
            
    </div>
    </div>
     
    <section class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                   &copy; 2014 Gen.com </a> 
                </div>

            </div>
        </div>
    </section>
    
    <script src="<?php echo base_url ?>assets/js/bootstrap.js"></script>	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.6/js/jquery.fancybox.min.js"></script>	
    
    <script type="text/javascript">
		$(document).ready(function() {
			$("#picpop").fancybox({
				  helpers: {
					  title : {
						  type : 'float'
					  }
				  }
			  });
		});
    </script>
  
</body>
</html>
