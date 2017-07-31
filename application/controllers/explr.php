<?phpclass explr extends CI_Controller{// Transform XML into other XML format using XSLTpublic function index(){    $this->load->view('Home');}// Transform XML into other XML format using XSLTpublic function converteads(){    $files = glob("application/eads/*xml");    if (is_array($files)) {        foreach ($files as $filename) {            $ead_doc = new DOMDocument();            $ead_doc->load($filename);            $file = basename($filename);            $newString = str_replace("xmlns=\"http://ead3.archivists.org/schema/\"", "", $ead_doc->saveXML());            file_put_contents("application/ceads/$file", $newString);            $new_ead_doc = new DOMDocument();            $new_ead_doc->load("application/ceads/$file");            $xsl_doc = new DOMDocument();            $xsl_doc->load("application/xslt/new_ead_solr.xsl");            $proc = new XSLTProcessor();            $proc->importStylesheet($xsl_doc);            $newdom = $proc->transformToDoc($new_ead_doc);            $newdom->save("application/solr_xmls/" . $file) or die("Error");        }        $convertedFiles = glob("application/solr_xmls/*xml");        $convertedFileCount = sizeof($convertedFiles);        $filecount = sizeof($files);        $data['filecount'] = $filecount;        $data['convertedFileCount']= $convertedFileCount;        $data['status']= 200;        echo "Total Number of convered documents=".$convertedFileCount;       // $this->load->view('Home',$data);    }else{        $data['status']= 404;        $this->load->view('Home',$data);    }}}?>