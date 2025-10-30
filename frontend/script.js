const form = document.getElementById('form-login');
const btn_check_auth = document.getElementById('btn_check_auth');

    // Login
    form.addEventListener('submit', function(event) {
        event.preventDefault() // impede o reload automatica apos o envio do formulario

        const formData = new FormData(form); // captura todos os campos do formulario

        // conexao com o backend via ajax:
        const req = new XMLHttpRequest();
        req.open('POST', 'http://localhost:3000/login.php', true); // true = assincrona

        req.onreadystatechange = function() { // sera executado sempre que o estado de requisicao mudar
            if (req.readyState === 4 && req.status === 200) {
                // console.log(req.responseText); // resposta do servidor

                const token = req.responseText;
                sessionStorage.setItem('session', token);
            }
        };

        req.send(formData);
        
    });

    // Verificar autenticação
    btn_check_auth.addEventListener('click', () => {
        try {
            const authSession = sessionStorage.getItem('session'); // pega o token
            const req = new XMLHttpRequest();
            req.open('GET', 'http://localhost:3000/auth.php', true);
            req.setRequestHeader('Authorization', `Bearer ${authSession}`);

            req.onreadystatechange = function() {
                if (req.readyState === 4 && req.status === 200) {
                    const response = JSON.parse(req.responseText);
                    console.log(response); // resposta do servidor
                }
            };

            req.send();
        } catch (error) {
            console.log(error);
        }
    });