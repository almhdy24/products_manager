async function loadCreators() {
  const url = "assets/files/creators.json";
  let users = await (await fetch(url)).json();

  let html = ``;
  for (let index = 0; index < users.length; index++) {
    const user = users[index];
    html += `
        <tr>
        <td>${user.id}</td>
        <td>${user.name}</td>
        <td>${user.department}</td>
        </tr>
        `;
  }
  // resultsDiv.style.display = 'block';

  document.querySelector("#tbody").innerHTML = html;
}
async function login() {
  const URL = "api/login";
  let email = document.querySelector("#email").value;
  let password = document.querySelector("#password").value;
  let data = new FormData();
  data.append("email", email);
  data.append("password", password);
  let options = {
    method: "POST",
    body: data,
  };
  let res = await (await fetch(URL, options)).json();
  console.log(res);
  if ((await res.msg) == true) {
    alert("تم تسجيل الدخول بنجاح.");
    sessionStorage.setItem("isLoggedIn", true);
    sessionStorage.setItem("token", res.token);
    window.location.replace("index.html");
  }
  alert("فشل تسجيل الدخول");
}

async function search() {
  const searchText = document.getElementById("search").value.trim();
  const url = `api/search/?query=${searchText}`;
  const resultsDiv = document.getElementById("results");
  if (searchText == "") {
    alert("قم بادخال النص ليتم البحث!");
  } else {
    let products = await (await fetch(url)).json();
    if (products == false) {
      alert("لا نتائج");
      return;
    }
    console.log(products);
    let html = ``;

    html += `
              <tr>
              <td>${products["name"]}</td>
              <td>${products["price"]}</td>
              <td>${products["amount"]}</td>
              </tr>
              `;
    resultsDiv.style.display = "block";
    document.querySelector("#tbody").innerHTML = html;

    // for (const product of products) {
    //   html += `
    //           <tr>
    //           <td>${product['name']}</td>
    //           <td>${product['price']}</td>
    //           <td>${product['amount']}</td>
    //           </tr>
    //           `;
    //           resultsDiv.style.display = 'block';
    //           document.querySelector('#tbody').innerHTML = html;
    // }
  }
}
async function loadProducts() {
  let url = `api/fetchAll?token=${sessionStorage.getItem("token")}`;
  const resultsDiv = document.getElementById("results");

  let products = await (await fetch(url)).json();

  console.log(products);
  let html = ``;
  for (const product of products) {
    html += `
          <tr>
          <td>${product["name"]}</td>
          <td>${product["price"]}</td>
          <td>${product["amount"]}</td>
          <td><button Onclick='deleteProduct(${product["id"]})'>حذف</button</td>
          </tr>
          `;
    resultsDiv.style.display = "block";
    document.querySelector("#tbody").innerHTML = html;
  }
}

function logout() {
  sessionStorage.removeItem("isLoggedIn");
  sessionStorage.removeItem("token");
  window.location.replace("login.html");
}
async function add() {
  const URL = "api/insert";
  let name = document.querySelector("#name").value;
  let price = document.querySelector("#price").value;
  let amount = document.querySelector("#amount").value;
  let data = new FormData();
  data.append("name", name);
  data.append("price", price);
  data.append("amount", amount);
  data.append("token", sessionStorage.getItem("token"));
  let options = {
    method: "POST",
    body: data,
  };

  let res = await (await fetch(URL, options)).json();
  //  console.log(res)
  if (!res.msg) {
    if (res.name) {
      beautyToast.error({
        title: "خطأ",
        message: "الحقل الاسم إجباري !",
      });
    }
    if (res.price) {
      beautyToast.error({
        title: "خطأ",
        message: "الحقل السعر إجباري !",
      });
    }
    if (res.amount) {
      beautyToast.error({
        title: "خطأ",
        message: "الحقل الكمية إجبارية !",
      });
    }
    return;
  }
  var spinHandle = loadingOverlay.activate();
  setTimeout(function () {
    loadingOverlay.cancel(spinHandle);
    return false;
  }, 1500);
  setTimeout(function () {
    beautyToast.success({
      title: "نجاح",
      message: "تم إضافة المنتج بنجاح !",
    });

    beautyToast.info({
      title: "معلومات", // Set
      message: "سيتم إعادة توجيهك بعد قليل !", // Set the message of beautyToast
    });
  }, 2500);

  setTimeout(function () {
    window.location.href = "/";
  }, 5500);
}
async function deleteProduct(id) {
  const url = `api/delete/?id=${id}&&token=${sessionStorage.getItem("token")}`;
  let res = await (await fetch(url)).json();
  if (res.msg) {
    beautyToast.success({
      title: "نجاح",
      message: "تم حذف المنتج بنجاح !",
    });
  }
  loadProducts();
}
