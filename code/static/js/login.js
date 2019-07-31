function pwmd5(_id) {
	var re = /^[\w]{32}$/i;
	orgval = $('#'+_id).val();
	if(re.test(orgval)) return;
	md5val= $.md5(orgval);
	$('#'+_id).val(md5val);
}