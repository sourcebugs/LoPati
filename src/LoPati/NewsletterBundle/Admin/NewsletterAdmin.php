<?php
namespace LoPati\NewsletterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterAdmin extends Admin
{
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('dataNewsletter','date', array('label' => 'Data publicació', 'widget' => 'single_text', 'format' => 'dd-MM-yyyy'))	
		->add('pagines')
		->add('estat')


		->setHelps(array('dataNewsletter'=>'Format: dd-MM-yyyy'))
		;
	}
	
	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
	->addIdentifier('dataNewsletter',null,array('label'=>'Data newsletter', 'template'=>'NewsletterBundle:Admin:list_custom_dataNewsletter_field.html.twig'))
	->add('test')
	->add('estat',null, array('label'=>'Estat', 'template' => 'NewsletterBundle:Admin:list_custom_estat_field.html.twig'))
	->add('enviats')
	->add('subscrits')
	->add('iniciEnviament',null, array('label'=>'Inici enviament', 'template' => 'NewsletterBundle:Admin:list_custom_iniciEnviament_field.html.twig'))
	->add('fiEnviament',null, array('label'=>'Fi enviament', 'template' => 'NewsletterBundle:Admin:list_custom_fiEnviament_field.html.twig'))
	
	->add('_action', 'actions', array(
			'actions' => array(
					'preview' => array(
							'template' => 'NewsletterBundle:Admin:previewLink.html.twig'),
					'test' => array(
							'template' => 'NewsletterBundle:Admin:testLink.html.twig'),
					'enviar' => array(
							'template' => 'NewsletterBundle:Admin:enviar.html.twig'),
					
	), 'label' => 'Accions'))		
		;
	}
	protected function configureRoutes(RouteCollection $collection) {
		$collection->add('enviar',
				$this->getRouterIdParameter().'/enviar');
				$collection->add('preview',
						$this->getRouterIdParameter().'/preview');
				$collection->add('test',
						$this->getRouterIdParameter().'/test')
				
						;
	}
	protected $datagridValues = array(
			'_page' => 1,
			'_sort_order' => 'DESC', // sort direction
			'_sort_by' => 'dataNewsletter' // field name
	);
}
