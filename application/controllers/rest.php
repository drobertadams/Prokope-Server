<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** Controller for REST. */
class rest extends CI_Controller {

	/** Empty index. */
	public function index()
	{
		redirect('/');
	}

	/** Get a document and all associated content. */
	public function document($id)
	{
		$this->load->helper('my_document_helper');
		$data = get_document_components($id);
		$this->load->view('doc_view_xml', $data);
	}
}

/*
                self.response.out.write("""
                <document>
                    <text>
                        <title>%s</title>
                        <author>%s</author>
                        <content>%s</content>
                    </text>
                    %s
                    %s
                    %s
                </document>
                """ % (doc.title, doc.author, doc.content, comment, vocab, sidebar))
            return
            
        # If no document key is given, display a list of all owned documents.
        user = users.get_current_user()
        q = DocumentModel.all().filter("author =", user).order("title")
        results = q.fetch(100)
        self.response.out.write("<list>")
        for p in results:
            self.response.out.write("""
                <document>
                    <title>%s</title>
                    <key>%s</key>
                </document>""" % (p.title, p.key()) )
        self.response.out.write("</list>")
		*/
