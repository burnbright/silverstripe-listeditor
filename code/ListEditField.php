<?php

/**
* ListEditField is used in conjunction with ListEditForm
* to allow viewing and removing exiting list items.
* @link ListEditForm
*/
class ListEditField extends FormField{

	private static $allowed_actions = array(
		'remove',
		'edit'
	);

	private static $url_handlers = array(
		'$Action/$ID' => '$Action',
	);
	
	protected $list;

	public function __construct($name, DataList $list) {
		$this->list = $list;
		parent::__construct($name);
	}

	public function Link($action = null, $id = null) {
		$link = parent::Link($action);
		if($id){
			$link = Controller::join_links($link, $id);
		}
		return $link;
	}

	/**
	 * This is not a data field.
	 * @return boolean
	 */
	public function hasData(){
		return false;
	}

	/**
	 * Template access for the list;
	 * @return DataList list
	 */
	public function getEditList() {
		return $this->list;
	}

	public function EditLink($id) {
		if($obj = $this->list->byID($id)){
			if($obj->canEdit(Member::currentUser())){
				return HTTP::setGetVar($this->ID(), $id);
			}
		}
	}

	public function RemoveLink($id) {
		if($obj = $this->list->byID($id)){
			if($obj->canDelete(Member::currentUser())){
				return $this->Link("remove", $id);
			}
		}
	}

	public function remove() {
		if($obj = $this->list->byID($id = $this->request->param('ID'))){
			if($obj->canDelete(Member::currentUser())){
				$this->list->removeById($id);
			}
		}

		return Controller::curr()->redirectBack();
	}

	/**
	 * Allow DataObject specific templates,
	 * using the form DataObjectName_ListEditField.ss
	 * @return array templates
	 */
	public function getTemplates() {
		$templates = parent::getTemplates();
		array_unshift($templates, $this->list->dataClass()."_ListEditField");
		return $templates;
	}

}