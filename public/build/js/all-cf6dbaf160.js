$(document).ready(function() {
    $('.nav-toggle').on('click', function() {
        $('div.nav-right.nav-menu').toggleClass('is-active');
    })

    $('#regist-type').on('change',function(){
    	if(this.value == 'teacher'){
    		$('#regist-student_id').hide();
    	}else{
    		$('#regist-student_id').show();
    	}
    });
});


//# sourceMappingURL=all.js.map
