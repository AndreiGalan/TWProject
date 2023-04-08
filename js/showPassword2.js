const togglePassword2 = document.querySelector("#togglePassword2");
        const password2 = document.querySelector("#password2");

        togglePassword2.addEventListener("click", function () {
            // toggle the type attribute
            const type2 = password2.getAttribute("type") === "password" ? "text" : "password";
            password2.setAttribute("type", type2);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });