<?php
namespace CeusMedia\WebServer\Response;
class Index/* extends \CeusMedia\WebServer\ResponseAbstract*/ {

	protected $config;

	public function __construct($config){
		$this->config   = $config;
	}

	public function handle($request, $response) {
        $root   = $this->config['docroot'];
		$path   = parse_url($request->getUrl(), PHP_URL_PATH);
		$list	= array();
		$index	= new \FS_Folder_Lister($root.$path);
		foreach($index->getList() as $item) {
			$link	= \UI_HTML_Tag::create( 'a', $item->getFilename(), array(
				'href'	=> $path.'/'.$item->getFilename()
			) );
			$list[]	= \UI_HTML_Tag::create('li', $link);
		}
		$list	= \UI_HTML_Tag::create('ul', $list);
		$template = new \CeusMedia\TemplateEngine\Template();
		$template->setTemplatePath($this->config['errorpages']);
		$template->addPlugin(new \CeusMedia\TemplateEngine\Plugin\Inclusion());
		$content  = $template->renderFile('index.html', array(
			'list'   => $list,
			'path'   => $path,
			'host'   => $this->config['host'],
			'port'   => $this->config['port'],
		));
		$response->addHeader(new \CeusMedia\WebServer\Header('Content-Type', 'text/html'));
//		$response->addHeader(new \CeusMedia\WebServer\Header('Content-Length', strlen($content)));
		return $content;
	}
}
?>
