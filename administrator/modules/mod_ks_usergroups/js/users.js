var UserGroupsModule='';

jQuery(document).ready(function(){

	UserGroupsModule=new KMListModule({
		'module':'mod_km_usergroups',
		'view':'users',
		'table':'users',
		'sortable':false
	});
	UserGroupsModule.list=UsersList;

});