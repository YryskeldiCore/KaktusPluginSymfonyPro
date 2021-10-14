let header = document.querySelector("h1")
let authors = document.querySelectorAll('.Article--author')
let publishAt = document.querySelector('.Article--createAt')

const btn = document.createElement("button")
btn.classList.add('plugin-btn')
btn.style.cssText = `
    display: block;
    margin: 0 auto;
    width: 300px;
    height: 40px;
    color: white;
    background-color: #00BFFF;
    border: 2px solid dark;
    border-radius: 8px;
    cursor:pointer;
`;
btn.innerText = 'Получить данные!'
header.after(btn)

const authorsArr = []

authors.forEach(author => {
    let authorText = author.textContent.trim();
    authorsArr.push(authorText);
})

headerText = header.textContent.trim()
publishAtText = publishAt.textContent.trim()

const formData = new FormData()
formData.append('name', headerText)
formData.append('publishAt', publishAtText)

for (let i = 0; i < authorsArr.length; i++){
    formData.append('authors[]', authorsArr[i])
}

const postData = async (url, data) => {
    const res = await fetch(url, {
        method: "POST",
        body: data
    })
    if(!res.ok){
        throw new Error(`${res.status}`)
    }
    return await res.json()
}

btn.addEventListener('click',() => {
    postData('http://127.0.0.1:8000/article/create', formData)
        .then(res => {
            console.log(res)
            const btnRemove = document.querySelector('.plugin-btn');
            btnRemove.remove()
            const btn = document.createElement("button")
            btn.textContent = 'Данные успешно отправлены!';
            btn.style.cssText = `
                display: block;
                margin: 0 auto;
                width: 300px;
                height: 40px;
                color: white;
                background-color: #32CD32;
                border: 2px solid #006400;
                border-radius: 8px;
                cursor:pointer;
            `;
            header.after(btn)
        })
        .catch(error => {
            console.log(error)
        })
});