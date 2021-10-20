var domain = 'http://www.narutogame.com.br';


/**
 * Abre uma popup para orkut
 * @param title
 * @param url
 */
function sendOrkut( title, url ) {
    window.open('http://promote.orkut.com/preview?nt=orkut.com&tt='+ encodeURIComponent(title) +'&du='+ encodeURIComponent(url),'windowOrkut', "width=650,height=500");
}

/**
 * Abre uma popup para Myspace
 * @param title
 * @param url
 */
function sendMyspace( title, url )
{
    var targetUrl = 'http://www.myspace.com/index.cfm?fuseaction=postto&t=' + encodeURIComponent(title)
    + '&u=' + domain + url;
    window.open(targetUrl, 'ptm', 'height=450,width=550').focus();
}

/**
 * Abre uma popup para Twitter
 * @param title
 * @param url
 */
function sendTwitter( title, url )
{
    var targetUrl = 'http://twitter.com/share?url=' + encodeURIComponent(url) +'&text='+ encodeURIComponent(title + ' em ') +'&via=narutogame';
    window.open(targetUrl, 'ptm', 'height=450,width=650').focus();
}


/**
 * Abre uma popup para facebook
 * @param title
 * @param url
 */
function sendFacebook( title, url )
{
    var targetUrl = 'http://www.facebook.com/share.php?t='+ encodeURIComponent(title) +'&u='+ encodeURIComponent(url);
    window.open(targetUrl, 'ptm', 'height=450,width=600').focus();
}


/**
 * Class Latest news
 * Lista as ultimas notÃƒÂ­cias
 */
function LatestNews() {

    this.content = document.getElementById( 'box-content' );
    this.positionTop    = 0;
    this.heightNews     = 61;
    this.interval;
    this.positionBefore = 0;
    this.positionAfter  = 61;
    this.countClick     = 1;
    this.LenghtNews;
    this.AmountNews;
    this.CorFonte;
    this.ObjectNews;
    
    this.setStyle = function( obj ) {
        if ( obj.corTitulos != undefined ){
            $('#button-descer,#button-subir').css( {'background-color':'#'+obj.corMenus} );
            this.CorFonte = obj.corTitulos;
        } else {
            this.CorFonte = '';
        }
    }

    this.SortObject = function( object ) {
        
    }

    /*
     * Desabilita os campos quando chega no limite 
     * de notÃƒÂ­cias
     */
    this.DisabledButton = function( numNews ) {
        $('.view').removeClass('view');
        $('#noticia-'+numNews+' .container-noticia').addClass('view');
        if ( numNews == 1 ) {
            $('#button-subir').attr('class','disabled');
            if ( this.AmountNews >= this.LenghtNews  ){
               $('#button-descer').attr('class','disabled');
            } else {
               $('#button-descer').attr('class','enabled');
            }
        } else if ( numNews >=  this.LenghtNews  ) {
            $('#button-descer').attr('class','disabled');
            $('#button-subir').attr('class','enabled');
        } else {
            if ( numNews > 0 && numNews < this.LenghtNews  )
            {
               $('#button-descer,#button-subir').attr('class','enabled');
            }
        }

        if ( this.ObjectNews != undefined ) {
             var nota = $('#noticia-'+ numNews).attr('nota');
             $('.ultimas-data').empty().append( this.ObjectNews[nota].date  );
            
             if ( getCookie('data_news') != this.ObjectNews[nota].date ){
                    $('.ultimas-data').css({'background':'#FFFFDF','border-color':'#FDFF00'});
                    setTimeout(function(){
                        $('.ultimas-data').css({'background':'#efefef','border-color':'#CCCCCC'});
                    },300);
                 setCookie('data_news',this.ObjectNews[nota].date,1);
             }
             
        }
        
    }

    var ProcessClick = false;

    /**
     * Move o scroll da lista de notÃ­cia
     */
    this.MoveToUp = function() {

        /**
         * Limpa os intervalos
         */
        var instance = this;

            /**
             * Soma atÃ© nÃºmeor de notÃ­cias
             */
            if ( instance.countClick < this.LenghtNews ) {
                instance.countClick++;
            }

            /**

             * Desabilita o os botÃµes
             * quando chega no limite
             */
            instance.DisabledButton( instance.countClick );


        if ( instance.countClick  <= ( this.LenghtNews - this.AmountNews + 1 ) ) {
            if ( ProcessClick === false ) instance.positionBefore = ( instance.countClick - 2) * instance.heightNews;
            instance.positionAfter  = (instance.countClick - 1) * instance.heightNews;
            if ( ProcessClick === false ) {
                clearInterval( instance.interval );
                instance.interval = window.setInterval(function() {
                    ProcessClick = true;
                    if ( instance.positionBefore >= instance.positionAfter ) {
                        clearInterval(instance.interval);
                        ProcessClick = false;
                    }
                    instance.content.scrollTop = instance.positionBefore++;
                },5);
            }
        }
    }
    
    /**
     * Move o scroll da lista de notÃ­cia
     */
    this.MoveToDown = function() {
        /**
         * Limpa os intervalos
         */
        var instance = this;
            
            if ( instance.countClick <= 1 ) return false;
            instance.countClick--;
            instance.DisabledButton( instance.countClick );
        if ( instance.countClick  <= ( this.LenghtNews - this.AmountNews ) ) {
            instance.positionBefore = (instance.countClick - 1) * instance.heightNews;
            if ( ProcessClick === false ) instance.positionAfter  =  instance.countClick * instance.heightNews;

            if ( ProcessClick === false ) {
                clearInterval( instance.interval );

                instance.interval = window.setInterval(function() {
                    ProcessClick = true;
                    if ( instance.positionBefore >= instance.positionAfter ) {
                        clearInterval(instance.interval);
                        ProcessClick = false;
                    }
                    instance.content.scrollTop = instance.positionAfter--;
                },5);
            }
        }
    }

    /**
     * Adiciona as notÃ­cias no documento
     */
    this.AppendNews = function( object ) {
        var instance = this;
        instance.ObjectNews = object;

        var boxContentHeight = (this.AmountNews * this.heightNews) - 2;
        $('#box-content').height( boxContentHeight );
        var titulo = '';
        var HTML   = '';
        var count = 1;
        
        /**
         * Faz ordenaÃ§Ã£o dos dados
         */
        var sortObj = [];
        for( idNota in object ) {
            var date = object[idNota].date.split(',')[1];
            var dateString = date.replace(' ','').split('/');
            var ano = String(dateString[2]);
            var mes = String(dateString[1]);
            var dia = String(dateString[0]);
            sortObj.push( [parseInt(ano + mes + dia + object[idNota].hour.replace('h','')),idNota] );
        }
        
        sortObj.sort();
        sortObj.reverse();

        for ( var i in sortObj )
        {
                var IdNota = sortObj[i][1];
                
                HTML += '<div id="noticia-'+ count +'" nota="'+ IdNota +'" class="noticia">';
                if ( count == 1 ) {
                    $('.ultimas-data').empty().append( object[IdNota].date  );
                    setCookie('data_news',object[IdNota].date,1);
                    HTML += '<div class="container-noticia view">';
                } else {
                    HTML += '<div class="container-noticia">';
                }
                    HTML += ' <div class="ultimas-hora">'+ object[IdNota].hour +'</div>';
                    HTML += ' <div class="ultimas-titulo">';
                    HTML += '    <a target="_parent" title="'+ object[IdNota].title +'"alt="'+ object[IdNota].title +'" style="color:#'+ this.CorFonte +';" href="'+ domain + object[IdNota].url +'">'+ object[IdNota].title +'</a>';
                    HTML += ' </div>';
                    HTML += ' <div class="ultimas-compartilhar">';
                    HTML += '   <table>';
                    HTML += '     <tr>';
                    HTML += '       <td><a class="share-button-twitter share-buttons"  alt="Compartilhe com seus amigos!" title="Compartilhe com seus amigos!" href=\'javascript:sendTwitter('+ JSON.stringify(object[IdNota].title) +',"'+ object[IdNota].url +'")\'>&nbsp;</a></td>';
                    HTML += '       <td><a class="share-button-facebook share-buttons" alt="Compartilhe com seus amigos!" title="Compartilhe com seus amigos!" href=\'javascript:sendFacebook('+ JSON.stringify(object[IdNota].title) +',"'+ object[IdNota].url +'")\'>&nbsp;</a></td>';
                    HTML += '     </tr>';
                    HTML += '     <tr>';
                    HTML += '       <td><a class="share-button-orkut share-buttons"    alt="Compartilhe com seus amigos!" title="Compartilhe com seus amigos!" href=\'javascript:sendOrkut('+ JSON.stringify(object[IdNota].title) +',"'+ object[IdNota].url +'")\'>&nbsp;</a></td>';
                    HTML += '       <td><a class="share-button-myspace share-buttons"  alt="Compartilhe com seus amigos!" title="Compartilhe com seus amigos!" href=\'javascript:sendMyspace('+ JSON.stringify(object[IdNota].title) +',"'+ object[IdNota].url +'")\'>&nbsp;</a></td>';
                    HTML += '     </tr>';
                    HTML += '   </table>';
                    HTML += '</div>';
                HTML += '</div>';
            HTML += '</div>';
            count++;
        }
        $('#box-content').append( HTML );
   }
}
