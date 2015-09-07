function SmmTrackedUser(info)
{
	if (info['vk_user_id'] != '')
	{
		KMSetSessionVariable();
		var data = {};
		
		data['vk_user_id'] = info['vk_user_id'];
		data['task'] = 'pluginAction';
		data['plugin'] = 'smmhunter';
		data['action'] = 'saveUser';
		data['format'] = 'json';
		
        jQuery.ajax({
            url: 'index.php?option=com_ksenmart',
            method: 'post',
            data: data
        });		
	}
}