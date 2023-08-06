
if (simple.getCookie('mode') == 'dark') {
    darkmode(true);
    s('.switch input[type="checkbox"]')[0].checked = true;
}
s(document).on('click', '.switch input[type="checkbox"]', function() {
    s('body').css({
        "-webkit-transition": ".5s",
        "transition": " .5s",
    });
    if (simple.getCookie('mode') != 'dark') {
        darkmode(true);
        this.checked = true;
        simple.setCookie('mode', 'dark', 30);

    } else {
        darkmode('false');
        simple.setCookie('mode', 'light', 30);
    }
})

function darkmode(state) {
    if (state == true) {
        s(':root')[0].style.setProperty('--black', '#eff2f6');
        s(':root')[0].style.setProperty('--front', '#1f2f42');
        s(':root')[0].style.setProperty('--extra-light-grey', '#293e56');
        s(':root')[0].style.setProperty('--active', 'white');
        s(':root')[0].style.setProperty('--dim-grey', '#8a9ab0');
        s(':root')[0].style.setProperty('--dim-grey', 'var(--extra-light-grey)');
        //change code theme
    } else {
        s(':root')[0].style.setProperty('--black', '#1f2f42');
        s(':root')[0].style.setProperty('--front', 'white');
        s(':root')[0].style.setProperty('--extra-light-grey', '#f6f8fa');
        s(':root')[0].style.setProperty('--active', '#1f2f42');
        s(':root')[0].style.setProperty('--dim-grey', '#dee6ed');
    }

}
s(document).on('click', '#dark-mode', function() {
    if (simple.getCookie('mode') != 'dark') {
        darkmode(true);
        simple.setCookie('mode', 'dark', 30);
    } else {
        darkmode('false');
        simple.setCookie('mode', 'light', 30);
    }
});

function addCodeTagFeature(el) {
    //copy code
    const copyButtonLabel = `
<svg height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'>
<path d='M7 4V2h10v2h3.007c.548 0 .993.445.993.993v16.014a.994.994 0 0 1-.993.993H3.993A.994.994 0 0 1 3 21.007V4.993C3 4.445 3.445 4 3.993 4H7zm0 2H5v14h14V6h-2v2H7V6zm2-2v2h6V4H9z' fill='currentColor'></path>
</svg>`;

    // use a class selector if available
    let blocks = document.querySelectorAll(el + " pre");

    blocks.forEach((block) => {
        // only add button if browser supports Clipboard API
      
        if (navigator.clipboard) {
            let button = document.createElement("span");
            button.innerHTML = copyButtonLabel;
            s(button).addClass('copy-btn');
            
            button.addEventListener("click", async () => {
                await copyCode(block, button);
            });

            if (block.querySelector('code')) {
                const codeTag = block.querySelector('code[class*="language-"]');
                let topbar = document.createElement('div');
                s(topbar).addClass('tools');
                if (codeTag != undefined) {
                    var arr = s(codeTag).attr('class').split(" ");
                    var language = arr.filter(e => e !== 'hljs')[0].split("-").filter(e => e !== 'language')[0];
                    switch (language) {
                        case 'html':
                            language = 'HTML'
                            break;
                        case 'css':
                            language = 'CSS'
                            break;
                        case 'javascript':
                            language = 'JS';
                            break;
                        case 'php':
                            language = 'PHP'
                            break;
                        case 'python':
                            language = 'Python'
                            break;
                        case 'ruby':
                            language = 'Ruby'
                            break;

                    }
                    const langFlag = document.createElement("span");
                    s(langFlag).addClass('langFlag');
                    langFlag.innerText = language;
                    if (language != 'undefined') {
                        topbar.appendChild(langFlag);
                        topbar.appendChild(button);
                        block.appendChild(topbar);
                    }
                }
            }
        }
    });

    async function copyCode(block, button) {
        let code = block.querySelector("code");
        let text = code.innerText;
        await navigator.clipboard.writeText(text);
        button.innerHTML = `
    <svg height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'>
        <path d='M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z' fill='currentColor'></path>
    </svg>`;
        setTimeout(() => {
            button.innerHTML = copyButtonLabel;
        }, 2000);
    }

}

spa.init({
    loader: '.loader',
    saveFomResults: true,
    scrollBehavior: "smooth",
    script: function() {
        hljs.configure({
            languages: []
        });
        hljs.highlightAll();
        addCodeTagFeature('.markdown');
        if (simple.getCookie('mode') == 'dark') {
            darkmode(true);
        }

        //Google analytics (gtag.js) 
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-G5WHF5KQGD');
    },
    requestStart: function() {
        s('.loader').show();
    },
    requestComplete: function() {
        s('.loader').css({
            width: '100%'
        });
        s('.loader').hide();
    },
    requestError: function() {
        s('.loader').css({
            width: '100%'
        });
        s('.loader').hide();
        alert('An error occued :(');
    },

});
s(document).on('click','.comment-box button[type="submit"]',function(){

    let name=s('.comment-box [name="name"]').val();
    let email=s('.comment-box [name="email"]').val();
    let comment=s('.comment-box [name="comment"]').val();
    let  parent_id=s('.comment-box').attr('parent-id');
    let  page_id=s('.comment-box').attr('page-id');
    let isEmpty=[];
    if(!name){
        isEmpty.push('Name')
    }
    if(!comment){
        isEmpty.push('Comment')
    }
    if(isEmpty.length>0){
        let isAre=' is';
        if(isEmpty.length>1) isAre=" are";
        s('.comment-box .err').html(isEmpty.join(', ')+isAre+' mendatory');
        return;
    }
    s(this).attr('disabled','true');
    s(this).html('Posting...');
    let self=this;
    simple.ajax({
        url:'api/comment',
        method:'post',
        data:{
            name:name,
            email:email,
            comment:comment,
            parent_id:parent_id,
            page_id:page_id,
            remember:remember.checked,
            type:'add'
        },
        success(res){
            if(res=='E1') return;
            if(s('.comments')[0].innerText.trim()=="No comments"){
                s('.comments').html(" ");
            };
            s('.comments').prepend(res);
        },
        fail(err){
            console.log(err)
        },
        anyway(){
            s(self).removeAttr('disabled');
            s(self).html('Post a comment');
        }
    })
});
s(document).on('click','.load-cmn-btn',function(){
    let  page_id=s('.comment-box').attr('page-id');
    let  page=s(this).attr('page');
    let placeholder=`
    <div class="comment  placeholder">
        <div class="info"></div>
        <div class="text"></div>
    </div>
    <div class="comment  placeholder">
        <div class="info"></div>
        <div class="text"></div>
    </div>
    <div class="comment  placeholder">
        <div class="info"></div>
        <div class="text"></div>
    </div>
    <div class="comment  placeholder">
        <div class="info"></div>
        <div class="text"></div>
    </div>
    `;
    let self=this;
    s('.comments').html(placeholder);
    simple.ajax({
        url:'api/comment',
        method:'post',
        data:{
            page_id:page_id,
            type:'all',
            page:page
        },
        success(res){
            setTimeout(() => {
                s('.comments').html(res);
                self.remove();
            }, 0);
        },
        fail(err){
            console.log(err)
        }
    })
});

s(document).on('click','.comment-box .close',function(){
    s('.comment-box').attr('parent-id','0');
    s('.comment-box').removeAttr('uid');
    s('.comment-box .heading').html('Write comment');
});
s(document).on('click','.comment .reply',function(){
    let commetId=s(this).attr('comment-id');
    s('.comment-box').attr('parent-id',commetId);
    s(('textarea[name="comment"]'))[0].focus();
    s('.comment-box .heading').html('Replying to @'+s('.comment[comment-id="'+commetId+'"] .name').html()+'<span class="close">Cancel</span>')
});

s(document).on('click','.comment .delete',function(){
    let confrim=confirm('Are you sure ? ');
    if(!confrim) return;
   let  uid=s(this).attr('uid');
   this.innerHTML="Deleting..."
   let  page_id=s('.comment-box').attr('page-id');
   let commetId=s(this).attr('comment-id');
   let self=this;
    simple.ajax({
        url:'api/comment',
        method:'post',
        data:{
            type:'delete',
            uid:uid,
            page_id:page_id,
        },
        success(res){
            if(res=='deleted'){
                s(`.comment[comment-id="${commetId}"]`).remove()
            }
        },
        fail(err){
            console.log(err)
        }
    })
});
//story play pause
function play(id){
    s('#'+id)[0].play();
    s('.pause-btn').show();
    s('.play-btn').hide();
}
function pause(id){
    s('#'+id)[0].pause();
    s('.pause-btn').hide();
    s('.play-btn').show();
}