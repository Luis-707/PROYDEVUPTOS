async function peticion(url = "", data = {}, metodo = "POST", callback = null) {

    $.ajax({
        data: data,
        url: url,
        type: metodo,
        dataType:'json',
        success:  function (resp)
        {
            // console.log(resp)
            if(resp){
                typeof callback === 'function' && callback(resp);
            }
        }
    });
}