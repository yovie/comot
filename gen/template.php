<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>FREE RESPONSIVE HORIZONTAL ADMIN</title>
    <link href="<?php echo base_url ?>assets/css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url ?>assets/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url ?>assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
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
                    <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel" >
                   
                    <div class="carousel-inner">
                        <?php $nn = 0; foreach($the_posts as $pp) :?>
                        <div class="item <?php echo ($nn==0)?'active':''?> ">

                            <img src="<?php echo base_url . 'content/' . $pp->category . '/' . $pp->picture ?>" alt="" />
                           
                        </div>
                        <?php $nn++; endforeach; ?>
                    </div>
                    
                     <ol class="carousel-indicators">
						<?php $mm = 0; foreach($the_posts as $pp) :?>
							<li data-target="#carousel-example" data-slide-to="<?php echo $mm ?>" class="<?php echo ($mm==0)?'active':''?>"></li>
                        <?php $mm++; endforeach; ?>
                    </ol>
                    
                     <a class="left carousel-control" href="#carousel-example" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					  </a>
					  <a class="right carousel-control" href="#carousel-example" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					  </a>
                </div>
                
                <div class="content">
					
					<?php if(isset($the_post)): ?>
						<h1><?php echo $the_post->title ?></h1>
						<?php echo $the_post->body ?>
						<?php if(!empty($the_post->picture)): ?>
							<img src="<?php echo base_url . 'content/' . $the_category->name . '/' . $the_post->picture ?>"  width="100%"/>
						<?php endif; ?>
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
  
</body>
</html>
