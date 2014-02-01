var UserGroupsModule=new KMListModule({
	'module':'mod_km_usergroups',
	'view':'users',
	'table':'users',
	'sortable':false
});

jQuery(document).ready(function(){

	UserGroupsModule.list=UsersList;

});