<!-- A div to contain the list of errors generated by JavaScript -->
<div id="errorList"></div>
<!-- global functions -->
<script>(function(g){var d=g.document,l=d.getElementById('errorList'),n=function(m,t){var b=d.createElement('div');b.className='box errorBox ';b.innerHTML=m;l.appendChild(b);t=t||5000;setTimeout(function(){l.removeChild(b)},t)};g.newError=n;g.rCookie={set:function(k,v,e,p,u,s){return(!k||/^(?:expires|max\-age|path|domain|secure)$/i.test(k))?!1:(d.cookie=encodeURIComponent(k)+"="+encodeURIComponent(v)+(e?(e.constructor===Number?(e===Infinity?"; expires=Fri, 31 Dec 9999 23:59:59 GMT":"; max-age="+e):e.constructor===String?("; expires="+e):e.constructor===Date?("; expires="+e.toUTCString()):""):"")+(u?"; domain="+u:"")+(p?"; path="+p:"")+(s?"; secure":""),!0)},get:function(k){return k?decodeURIComponent(d.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*"+encodeURIComponent(k).replace(/[\-\.\+\*]/g,"\\$&")+"\\s*\\=\\s*([^;]*).*$)|^.*$"),"$1")):null},has:function(k){return !!k&&(new RegExp("(?:^|;\\s*)"+encodeURIComponent(k).replace(/[\-\.\+\*]/g, "\\$&")+"\\s*\\=")).test(d.cookie)},remove:function(k,p,u){return this.has(k)&&this.set(k,'',new Date(0),p,u)}}}(window))</script>
<!-- Logo -->
<a href="<?=$webRoot?>" class="logo-link" title="Home">
	<img src="<?=$webRoot?>/media/img/logo.svg" alt="Roomies" class="logo-img">
</a>