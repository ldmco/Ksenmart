<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewSeo extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksenmart&view=panel&component_type=trade');
		$this->path->addItem(JText::_('ksm_seo'));
		$this->seo_type=$this->state->get('seo_type');
		switch ($this->getLayout())
		{
			case 'seourlvalue':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/seourlvalue.js');
                $this->seourlvalue = $this->get('SeoURLValue');
                $this->title = JText::_('ksm_seo_seourlvalue_editor');
				break;		
			case 'seotitlevalue':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/seotitlevalue.js');
                $this->seotitlevalue = $this->get('SeoTitleValue');
                $this->title = JText::_('ksm_seo_seotitlevalue_editor');
				break;
			case 'seotext':
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/seotext.js');
                $model = $this->getModel();
                $seotext = $model->getSeoText();
                $model->form = 'seotext';
                $form = $model->getForm();
                if($form) $form->bind($seotext);
                $this->title = JText::_('ksm_seo_seotext_editor');
                $this->form = $form;
				break;					
			default:		
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/list.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/listmodule.js');				
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/seo.js');			
				switch($this->seo_type)
				{
					case 'seo-texts-config':
						$this->items=$this->get('ListItems');
						$this->total=$this->get('Total');					
						break;
					case 'seo-titles-config':
						$this->configs=$this->get('TitlesConfigs');	
						break;
					case 'seo-meta-config':
						$this->configs=$this->get('MetaConfigs');	
						break;	
					case 'seo-urls-config':
						$this->configs=$this->get('UrlsConfigs');	
						break;
				}
		}		
        parent::display($tpl);
    }

}