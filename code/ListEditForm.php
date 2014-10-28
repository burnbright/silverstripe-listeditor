<?php

class ListEditForm extends Form{

	private static $allowed_actions = array(
		'savenew',
		'save'
	);

	protected $list;
	protected $editing = false;

	public function __construct($controller, $name, DataList $list) {
		$this->list = $list;
		$class = $list->dataClass();
		$singleton = singleton($class);
		$fields = $singleton->getFrontEndFields();
		$fields->unshift(new ListEditField("EditList", $this->list));
		$fields->push(new HiddenField("ID","ID"));
		$actions = new FieldList(
			new FormAction("savenew", "Save ".$singleton->i18n_singular_name())
		);
		parent::__construct($controller, $name, $fields, $actions);
		$this->setupEditingMode();
		$singleton->extend('updateListEditForm', $this);
		//all fields are required
		if(!$this->validator){
			$this->setValidator(new RequiredFields(
				array_keys($fields->saveableFields())
			));
		}
	}

	/**
	 * Convert this form in a mode to edit an existing
	 * record, but only if an ID is given.
	 */
	protected function setupEditingMode() {
		if($obj = $this->editableObject()){
			$this->loadDataFrom($obj);
			$this->Actions()->fieldByName("action_savenew")
				->setName("action_save")
				->setFullAction("action_save");
		}
	}

	public function savenew($data, $form) {
		$class = $this->list->dataClass();
		$obj = $this->list->newObject();
		if($obj->canCreate(Member::currentUser())){
			$form->saveInto($obj);
			$obj->write();
			$this->list->add($obj);
			$form->sessionMessage("New ".strtolower($obj->i18n_singular_name())." has been added.", "good");
		}

		return $this->controller->redirectBack();
	}

	public function save($data, $form){
		if(isset($data['ID']) && $obj = $this->editableObject((int)$data['ID'])) {
			$form->saveInto($obj);
			$obj->write();
			$form->sessionMessage(strtolower($obj->i18n_singular_name())." has been updated.", "good");
		}else{
			$form->sessionMessage(strtolower($obj->i18n_singular_name())." could not be updated.", "bad");
		}
		//TODO: strip out edit id
		return $this->controller->redirectBack();
	}

	/**
	 * Get the editable object.
	 */
	protected function editableObject($id = null) {
		if(!$id && $editfield = $this->Fields()->fieldByName("EditList")){
			$id = $this->controller->getRequest()->getVar($editfield->ID());
		}
		$obj = $this->list->byID($id);
		if($obj && $obj->canEdit(Member::currentUser())){
			$this->editing = true;
			return $obj;
		}
	}

	public function isEditing(){
		return $this->editing;
	}

}
