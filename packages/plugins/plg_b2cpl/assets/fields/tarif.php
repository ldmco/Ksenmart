<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JFormFieldTarif extends JFormField
{

	protected $type = 'Tarif';

	public function getInput()
	{
		if ($this->form->tarif->Наименование == 'пвз') {
			$html = 'Пункт самовывоза <small>(' . $this->form->tarif->Адрес . ')</small>';
		} else {
			$html = $this->form->tarif->Наименование;
		}

		return $html;
	}
}
