class Simple extends Array{
    constructor(el) {
        super();
        if (el == document || el instanceof HTMLElement) {
            this.push(el);
        } else {
            let elements=document.querySelectorAll(el);
            for(let i=0;i<elements.length;i++){
                this.push(elements[i]);
            }
        }
    }
    ready(callback){
        this[0].addEventListener('readystatechange', e => {
            if(this[0].readyState === "complete"){
                callback();
                return true;
            }
          });
    }
    each(callback) {
        if (callback && typeof(callback) == 'function') {
            for (let i = 0; i < this.length; i++) {
                callback(this[i], i);
            }
            return this;
        } 
    }
    siblings(){
        return  [...this[0].parentNode.children].filter(c=>c!=this[0])
    }
    addClass(className) {
        this.each(function(el) {
            el.classList.add(className);
        })
        return this;
    }

    removeClass(className) {
        this.each(function(el) {
            el.classList.remove(className);
        })
        return this;
    }

    hasClass(className) {
        return this[0].classList.contains(className);
    }

    css(propertyObject) {
        this.each(function(el) {
            Object.assign(el.style,propertyObject);
        })
        return this;
    }

    attr(attr, value = null) {
        let getattr = undefined;
        if (value) {
            this.each(function(el) {
                el.setAttribute(attr, value);
                getattr = this;
            });
        } else {
            getattr = this[0].getAttribute(attr);
        }
        return getattr;
    }
    removeAttr(attr){
        this.each(function(el) {
            el.removeAttribute(attr);
        });
        return this;
    } 
    remove(){
        this.each(function(el) {
            el.remove();
        });
        return this;
    } 
    focus(){
        this[0].focus();
    }
    val(data){
        if (data) {
            this.each(function(el) {
                el.value = data;
            })
        } else {
            return this[0].value;
        }
        return this;
    }
    html(data) {
        if (data) {
            this.each(function(el) {
                el.innerHTML = data;
            })
        } else {
            return this[0].innerHTML;
        }
        return this;
    }
    append(el){
        if(el.trim().startsWith('<') && el.trim().endsWith(">")){
            this[0].insertAdjacentHTML('beforeend', el);
        }else{
            this[0].append(el);
        }
        return this;
    }
    prepend(el){
        if(el.trim().startsWith('<') && el.trim().endsWith(">")){
            this[0].insertAdjacentHTML('afterbegin', el);
        }else{
        this[0].prepend(el);
        }
        return this;
    }
    hide() {
        this.each(function(el) {
            el.style.display = "none";
        });
        return this;
    }
    show() {
        this.each(function(el) {
            el.style.display = "block";
        });
        return this;
    }
    click(fn=null){
        if(!fn){
            this[0].click();
        }else{
            this.on('click',fn);
        }
    }
    on(event, child, callback = null, state = null) {
        if (callback != null) {
            let selector = child;
            this.each(function(element) {
                element.addEventListener(event, function(event) {
                    if (event.target.matches(selector + ', ' + selector + ' *')) {
                        callback.apply(event.target.closest(selector), arguments);
                    }
                }, false)
            })
        } else {
            callback = child;
            this.each(function(element) {
                if (state) {
                    element.addEventListener(event, callback, state);
                } else {
                    element.addEventListener(event, callback, false);
                }
            })
        }

        return this;
    }
}
const s = function(el) {
    return new Simple(el);
}
s.clean=function(node) {
    for (let n = 0; n < node.childNodes.length; n++) {
        let child = node.childNodes[n];
        if (
            child.nodeType === 8 ||
            (child.nodeType === 3 && !/\S/.test(child.nodeValue))
        ) {
            node.removeChild(child);
            n--;
        } else if (child.nodeType === 1) {
            this.clean(child);
        }
    }
}

s.parseHTML=function(str) {
    if(str instanceof HTMLElement){
        this.clean(str);
        return str;
    }
    if(str.nodeType==9){
        return str;
    }
    let parser = new DOMParser();
    let doc = parser.parseFromString(str, 'text/html');
    this.clean(doc.body);
    return doc.body;
}
s.ajax=function(args) {
        let url = args["url"];
        let method = "get";
        let success =function(){};
        let fail = function(){};
        let anyway = function(){};
        if(args['success']){
            success=args['success'];
        }
        if(args['anyway']){
            anyway=args['anyway'];
        }
        if(args['fail']){
            fail=args['fail'];
        }
        let xhttp = new XMLHttpRequest();
        xhttp.onerror = function(error){
            anyway();
            return fail(error);
        }
        xhttp.onload = function() {
            let response;
            anyway();
            if (this.readyState == 4 && this.status == 200) {
                let response="";
                try {
                    response=JSON.parse(this.responseText)
                } catch (e) {
                    response = this.responseText;
                    }
                return success(response);
            } else {
                return fail(this.status);
            }
            
        };
        let parameters="";
        if (args) {
            method = args["method"];
            if ('data' in args) {
                parameters = new URLSearchParams(args['data']).toString();
            }

        }
        if (method && method.toUpperCase()=='POST') {
            xhttp.open("POST", url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(parameters);
        } else if (!method || method.toUpperCase()=='GET'){
            xhttp.open("GET", url + "?" + parameters, true);
            xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhttp.send();
        }
    }
s.setCookie=function(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + decodeURIComponent(cvalue) + ";" + expires + ";path=/";
    }

s.getCookie=function(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == " ") {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
    }
const simple=s;