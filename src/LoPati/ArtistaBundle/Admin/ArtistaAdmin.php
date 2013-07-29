<?php

namespace LoPati\ArtistaBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\PageBundle\Model\PageInterface;


use Knp\Menu\ItemInterface as MenuItemInterface;
use LoPati\BlogBundle\Entity\Pagina;

class ArtistaAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('category', null, array('label' => 'Especialitat'))
            ->add('city', null, array('label' => 'Ciutat'))
            ->add('year', null, array('label' => 'Any'))
            ->add('webpage', null, array('label' => 'Web'))
            ->add('active', null, array('label' => 'Activat'))
            //->add('position', null, array('label' => 'Posició'))
		->add('summary', 'textarea', array('label' => 'Resum', 'required'  => false, 'attr'=>(array('style'=>'height:90px;'))))
		->add('description', 'textarea', array('attr' => array('class' => 'tinymce',
				 'data-theme'=>'simple',
				'style' => 'width: 600px; height: 400px;'),'label' => 'Descripció'))

		->with('Imatges')
            ->add('image1File', 'file', array('label' => 'Imatge 1', 'required'=>false))
            ->add('image1', null, array('label' => 'Nom imatge 1', 'required'=>false, 'read_only'=>true,))
            ->add('image2File', 'file', array('label' => 'Imatge 2', 'required'=>false))
            ->add('image2', null, array('label' => 'Nom imatge 2', 'required'=>false, 'read_only'=>true,))
            ->add('image3File', 'file', array('label' => 'Imatge 3', 'required'=>false))
            ->add('image3', null, array('label' => 'Nom imatge 3', 'required'=>false, 'read_only'=>true,))
            ->add('image4File', 'file', array('label' => 'Imatge 4', 'required'=>false))
            ->add('image4', null, array('label' => 'Nom imatge 4', 'required'=>false, 'read_only'=>true,))
            ->add('image5File', 'file', array('label' => 'Imatge 5', 'required'=>false))
            ->add('image5', null, array('label' => 'Nom imatge 5', 'required'=>false, 'read_only'=>true,))

		->with('Traduccions')
		->add('translations', 'a2lix_translations',array(
				'label'=> ' ',
				'required' => false,
				'fields' => array(                          // [Optionnal] Fields configurations. If not, auto detection from translatable annotations
						'summary'=>array(
								 'type' => 'textarea',
                                'label' => 'Resum',
								'attr'=>array(
										'style'=>'height:90px;width:480px;')),
						'description' => array(
								'label' => 'Descripció',                   // Custom label
								'attr' => array(
										'class' => 'tinymce',
										'data-theme'=>'simple',
										'style' => 'width: 600px; height: 400px; display: block;')),
						)
			
		))
		;
	}

	protected function configureListFields(ListMapper $mapper)
	{
		$mapper
		->add('id')
		->addIdentifier('name', null, array('label' => 'Nom'))
		->add('category', null, array('label' => 'Especialitat'))
        ->add('city', null, array('label' => 'Ciutat'))
        ->add('year', null, array('label' => 'Any'))
        ->add('webpage', null, array('label' => 'Web'))
		->add('active', null, array('label' => 'Actiu'))
        //->add('position', null, array('label' => 'Posició'))
        ;
	}
	

	protected $datagridValues = array(
			'_page' => 1,
			'_sort_order' => 'ASC', // sort direction
			'_sort_by' => 'name' // field name
	);
	
    /*
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('titol')
            ->add('actiu')
            ->add('portada')
            ->add('data_publicacio')
            ->add('categoria')
            ->add('subCategoria')
        ;
    }
	*/
}