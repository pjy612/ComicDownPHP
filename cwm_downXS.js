//$.myDecrypt({content:temp1.chapter_content,keys:temp1.encryt_keys,accessKey: HB.config.chapterAccessKey})

await $.getScript('https://www.ciweimao.com/resources/js/jquery-plugins/jquery.base64.min.js');
await $.getScript('https://www.ciweimao.com/resources/js/enjs.min.js');
await $.getScript('https://www.ciweimao.com/resources/js/myEncrytExtend-min.js');
function sleep(delay)
{
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}
async function getContent(chapter_id){
    auth_body = await fetch('https://www.ciweimao.com/chapter/ajax_get_session_code',{
    method: 'POST',
    headers: {
    'Content-Type': 'application/x-www-form-urlencoded'
    },
    referrer:`https://www.ciweimao.com/chapter/${chapter_id}`,
    body:`chapter_id=${chapter_id}`
    }).then(rsp=>rsp.json());
    let {chapter_access_key} = auth_body;
    content_body = await fetch('https://www.ciweimao.com/chapter/get_book_chapter_detail_info',{
    method: 'POST',
    headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    },
    referrer:`https://www.ciweimao.com/chapter/${chapter_id}`,
    body:`chapter_id=${chapter_id}&chapter_access_key=${chapter_access_key}`
    }).then(rsp=>rsp.json());
    let {chapter_content,encryt_keys} = content_body;
    content = $.myDecrypt({content:chapter_content,keys:encryt_keys,accessKey:chapter_access_key});
    content = $(content).text()
    return content;
}
all_chapter_list = [];
group = $(".book-chapter .book-chapter-box");
group.each((k,v)=>{
    group_chapter_list = [];
    group_title = $('.sub-tit',v).text();
    chapter_list = $('.book-chapter-list li a',v);
    chapter_list.each((ka,va)=>{
        title = va.innerText;
        chapter_id = va.href.match(/(\d+)$/)[1];
        group_chapter_list.push({title,chapter_id})
    });
    all_chapter_list.push({group_title,group_chapter_list})
});
console.log(all_chapter_list);
bookname = $('.hd h3').text();
async function downAll(){
    var no = 0;
    for (il in all_chapter_list){
        v = all_chapter_list[il];
        let{group_title,group_chapter_list}= v;
        for (ix in group_chapter_list){
            let{title,chapter_id} = group_chapter_list[ix];
            content = '';
            content = await getContent(chapter_id);
            postdata = {bname:bookname,gname:group_title,cname:title,no:no,content:content};
            $.ajax({
                type: "POST",
                url: "http://127.0.0.1:88/dxs.php",
                data: postdata,
                async: false,
                success: function(data) {
                    console.log(data);
                }
            });
            no++;
            sleep(100);
        }
    }
}
await downAll();


