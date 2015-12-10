
function submit_confirm(txt)
{
    var txt;
    if (txt) txt = txt;
    else txt = "Simpan data ?";
    var r = confirm(txt);
    if (r == true) {
        // do something
    } else {
        return false;
    }
}

function clog(data)
{
    console.log(data);
}

function redirect(data)
{
    window.location.href=data;
}

function readURLpose(input, target) {
    console.log(input);
    if (input.files && input.files[0]) {

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+target).attr('src', e.target.result);
            $('#'+target).attr('width', '100px');
            // $('#'+target).attr('height', '200px');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function redirect(url)
{
  window.location.href=url;
}

function inputvisimisi(req, iddata)
{
  var id = $('.eselonid').val();
  
  if (iddata){
    redirect(basedomain+"renstra/editEselon/?req=" + req + "&parent_id="+id+"&id="+iddata);

  }else{
    redirect(basedomain+"renstra/editEselon/?req=" + req + "&parent_id="+id);
  }
  
}

function inputsasaran(req, iddata)
{
  var id = $('.eselonid').val();
  
  if (iddata){
    redirect(basedomain+"renstra/editSasaran/?req=" + req + "&parent_id="+id+"&id="+iddata);

  }else{
    redirect(basedomain+"renstra/editSasaran/?req=" + req + "&parent_id="+id);
  }
  
}

function inputdokumen(pid, req, iddata)
{
  var id = $('.eselonid').val();
  // clog(id);
  // return false;
  if (iddata){
    redirect(basedomain+"renstra/editDokumen/?pid="+ pid +"&req=" + req + "&parent_id="+id+"&id="+iddata);

  }else{
    redirect(basedomain+"renstra/editDokumen/?pid="+ pid +"&req=" + req + "&parent_id="+id);
  }
  
}