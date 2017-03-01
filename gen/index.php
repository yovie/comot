<?php
	require __DIR__ . '/vendor/autoload.php';
	
	
	const content_path	=	"content";
	

	use Spatie\Url\Url;
	use eden;
	use Lead\Dir\Dir;	
	
	$url = Url::fromString( $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
	
	$category = $url->getSegment(2);
	$subcategory = $url->getSegment(3);
	
	$database = eden('sqlite', '[' . getcwd() . '/database.db' . ']');
	
	
	function ScanContent(){
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
		return $files;
	}
	
	function PrepareDB(){
		
	}
	
	print_r( ScanContent() );

	
