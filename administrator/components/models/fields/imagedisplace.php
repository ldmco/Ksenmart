<?php 
defined( '_JEXEC' ) or die;

class JFormFieldImageDisplace extends JFormField
{
	protected $type = 'ImageDisplace';

	public function getInput()
	{
		$html='	
		<div class="field switch displace">
			<label class="cb-enable '.($this->value ? 'selected' : '').'" displace_val="1"><span>Да</span></label>
			<label class="cb-disable '.(!$this->value ? 'selected' : '').'" displace_val="0"><span>Нет</span></label>
		</div>
		<input type="hidden" name="'.$this->name.'" value="'.$this->value.'" />';

		return $html;
	}
}