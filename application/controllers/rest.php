<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for REST. */
class rest extends CI_Controller 
{

	/** Get a document and all associated content. */
	public function document($id)
	{
		$this->load->helper('my_document_helper');
		$data = get_document_components($id);
		$this->load->view('doc_view_xml', $data);
	}

	/** Get a list of everything that is available in the database. */
	public function index()
	{
	echo <<<EOT
<prokope>
	<author name="Cicero" icon="http://www.stenudd.com/myth/greek/images/plato.jpg">
	   <work name="Cicero Work 1" url="http://www.CiceroWork1.com"></work>
	   <work name="Cicero Work 2" url="http://www.CiceroWork2.com"></work>
	   <work name="Cicero Work 3" url="http://www.CiceroWork3.com"></work>
	   <work name="Cicero Work 4">
			<chapter name="Cicero Work 4 Chap. 1" url="www.CiceroWork4Chap1.com"></chapter>
			<chapter name="Cicero Work 4 Chap. 2" url="www.CiceroWork4Chap2.com"></chapter>
	   </work>
	   <work name="Cicero Work 5" url="http://www.CiceroWork5.com"></work>
	</author>
	<author name="Plato" icon="http://www.stenudd.com/myth/greek/images/plato2.jpg">
	   <work name="Plato Work 1" url="http://www.PlatoWork1.com"></work>
	   <work name="Plato Work 2" url="http://www.PlatoWork2.com"></work>
	   <work name="Plato Work 3">
			<chapter name="Plato Work 3 Chap. 1" url="www.PlatoWork3Chap1.com"></chapter>
			<chapter name="Plato Work 3 Chap. 2" url="www.PlatoWork3Chap2.com"></chapter>
	   </work>
	</author>
	<author name="Socrates" icon="http://www.stenudd.com/myth/greek/images/plato3.jpg">
	   <work name="Socrates Work 1" url="http://www.SocratesWork1.com"></work>
	</author>
	<author name="Aristotle" icon="http://www.stenudd.com/myth/greek/images/plato4.jpg">
	   <work name="Aristotle Work 1">
			<chapter name="Aristotle Work 1 Chap. 1" url="www.AristotleWork1Chap1.com"></chapter>
			<chapter name="Aristotle Work 1 Chap. 2" url="www.AristotleWork1Chap2.com"></chapter>
	   </work>
	</author>
</prokope>
EOT;
	}
}
