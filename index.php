<?php

/* -AFTERLOGIC LICENSE HEADER- */

class_exists('CApi') or die();

class CAddSuggestContactsExamplePlugin extends AApiPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);

		$this->AddHook('webmail.change-suggest-list', 'WebmailChangeSuggestList');
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sSearch
	 * @param array $aList
	 * @param array $aCounts
	 */
	public function WebmailChangeSuggestList($oAccount, $sSearch, &$aList, &$aCounts)
	{
		if ($oAccount)
		{
			// Your custom php logic
			$oRemoteDb = CRemoteApi::GetDB();
			$aResultContacts = $oRemoteDb->GetContacts($oAccount->Email, $sSearch);

			if (is_array($aResultContacts))
			{
				foreach ($aResultContacts as $sResultEmail)
				{
					$oContactItem = new CContactListItem();
					$oContactItem->Id = 0;
					$oContactItem->IdStr = '';
					$oContactItem->Name = '';
					$oContactItem->Email = $sResultEmail;
					$oContactItem->UseFriendlyName = true;
					$oContactItem->Global = true;
					$oContactItem->ReadOnly = true;
					$oContactItem->ItsMe = $oContactItem->Email === $oAccount->Email;

					$aList[] = $oContactItem;
				}
			}
		}
	}
}

return new CAddSuggestContactsExamplePlugin($this);
