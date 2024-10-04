let currentpage = 1;
let totalpages = 0;
// code for the event listener: search button
document.getElementById('searchbtn').addEventListener('click', function (event) {
    event.preventDefault(); 
    currentpage = 1; 
    fetchnews();
});
// code for the event listener: load more button
document.getElementById('loadmorebtn').addEventListener('click', function () {
    if (currentpage < totalpages) {
        currentpage++; 
        fetchnews(false); 
    }
});
// to define function fetch news
function fetchnews(clear = true) {
    const query = document.getElementById('searchQuery').value.trim();
    const category = document.getElementById('categoryFilter').value.trim();

    if (!query) {
        alert('Please enter a search query!');
        return;
    }

    const newsContainer = document.getElementById('news-container');
    if (clear) {
        newsContainer.innerHTML = ""; 
    }

    const apiKey = 'dc88ba1fac197ca590caebc3d43b538a'; 
    let apiUrl = `https://gnews.io/api/v4/search?q=${encodeURIComponent(query)}&token=${apiKey}&lang=en&max=10&page=${currentpage}`;

    if (category) {
        apiUrl += `&topic=${encodeURIComponent(category)}`;
    }

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data); 

            if (data.articles && data.articles.length > 0) {
                totalpages = Math.ceil(data.totalArticles / 10); 

                data.articles.forEach(article => {
                    console.log('Article Data:', article); 

                    const sourceName = article.source ? article.source.name : 'Unknown';
                    const datePublished = article.publishedAt ? new Date(article.publishedAt).toLocaleDateString() : 'Unknown';

                    const articleDiv = document.createElement('article');
                    articleDiv.classList.add('news-item');
                    articleDiv.innerHTML = `
                        <h2>${article.title}</h2>
                        <p>${article.description}</p>
                        <p><strong>Source:</strong> ${sourceName} | <strong>Date:</strong> ${datePublished}</p>
                        <a href="${article.url}" target="_blank" aria-label="Read more about ${article.title}">Read more</a>
                    `;
                    newsContainer.appendChild(articleDiv);
                });

                document.getElementById('loadmorebtn').style.display = currentpage < totalpages ? 'block' : 'none';
            } else {
                newsContainer.innerHTML = "<p>No articles found.</p>";
                document.getElementById('loadmorebtn').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching news:', error);
            newsContainer.innerHTML = "<p>Error fetching articles. Please try again later.</p>";
            document.getElementById('loadmorebtn').style.display = 'none';
        });
}
