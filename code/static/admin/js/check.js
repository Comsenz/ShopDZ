$(function(){
	//表格全选/全不选
    var obox = document.getElementById ("check-all"); 
    var ach=$('.check_sub');
    obox.onclick = function () 
    { 
        for ( var i = 0; i < ach.length; i++) 
        { 
            ach[i].checked = this.checked; 
        } 
    }; 
    for ( var i = 0; i < ach.length; i++) 
    { 
        ach[i].onclick = function () 
        { 
            if ( !this.checked ) 
            { 
                obox.checked = false; 
            }
            var flag = true; 
            for ( var i = 0; i < ach.length; i++) 
            { 
                if (!ach[i].checked) 
                { 
                    flag = false; 
                    break; 
                } 
            } 
            if (flag) 
            { 
                obox.checked = true; 
            } 
        }; 
    } 
})