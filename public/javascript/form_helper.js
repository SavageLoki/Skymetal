function getContent () {
    let textarea = document.getElementById("article_Content");
    let content = document.getElementById("my-content");
    textarea.value = content.innerText;

    let articleTitle = document.getElementById("article_title");
    let title = document.getElementById("my-title");
    articleTitle.value = title.innerText;

}