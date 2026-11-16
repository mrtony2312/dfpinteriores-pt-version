<footer class="footer">
    <div class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h3>Subscreva a nossa Newsletter</h3>
                <p>Receba promoções exclusivas e novidades no seu e-mail.</p>
            </div>
            <form class="newsletter-form" action="mailto:{{ config('mail.admin_address', 'contacto@dfpinteriores.com') }}" method="get" enctype="text/plain">
                <input type="hidden" name="subject" value="Subscrição Newsletter DFP Interiores">
                <input type="email" name="body" placeholder="O seu e-mail" required>
                <button type="submit">Subscrever</button>
            </form>
        </div>
    </div>

    <div class="destaques">
        <div class="container grid-4">
            <div class="item">
                <svg class="sprite-icon"><use href="/img/footer_icons.svg#apoio_cliente"></use></svg>
                <div class="text">
                    <h4>Apoio ao Cliente</h4>
                    <p>Telefone, e-mail ou chat.</p>
                </div>
            </div>
            <div class="item">
                <svg class="sprite-icon"><use href="/img/footer_icons.svg#entrega"></use></svg>
                <div class="text">
                    <h4>Entrega Rápida</h4>
                    <p>Em todo o país.</p>
                </div>
            </div>
            <div class="item">
                <svg class="sprite-icon"><use href="/img/footer_icons.svg#satisfacao"></use></svg>
                <div class="text">
                    <h4>Satisfação garantida</h4>
                    <p>Feedback dos nossos clientes.</p>
                </div>
            </div>
            <div class="item">
                <svg class="sprite-icon"><use href="/img/footer_icons.svg#qualidade"></use></svg>
                <div class="text">
                    <h4>Pagamento Seguro</h4>
                    <p>Vários métodos disponíveis.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="links">
        <div class="container grid-4">
            <div class="link-group">
                <h3>Links Úteis</h3>
                <ul>
                    <li><a href="/lojas">As nossas lojas</a></li>
                    <li><a href="/contactos">Contactos</a></li>
                    <li><a href="/ajuda/termos-e-condicoes">Termos e Condições</a></li>
                    <li><a href="/ajuda/politica-privacidade">Política de Privacidade</a></li>
                    <li><a href="/ajuda/politica-de-cookies">Política de Cookies</a></li>
                    <li><a href="/ajuda/politica-de-envios">Política de Envios e Entregas</a></li>
                    <li><a href="/ajuda/politica-de-devolucoes">Política de Devoluções e Reembolsos</a></li>
                    <li><a href="/ajuda/resolucao-alternativa-litigios">Resolução Alternativa de Litígios</a></li>
                    <li><a href="https://www.livroreclamacoes.pt/inicio/" target="_blank" rel="noopener">Livro de Reclamações</a></li>
                    <li><a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">Resolução de Litígios em Linha (UE)</a></li>
                </ul>
            </div>

            <div class="link-group">
                <h3>Horário</h3>
                <p>Segunda a Sexta: 09h - 22h</p>
                <p>Sábado: 9h - 18h<br><small>Exclusivo loja online</small></p>
            </div>

            <div class="link-group">
                <h3>Contactos</h3>
                <p>DFP Interiores, Unipessoal Lda</p>
                <p>Rua José Francisco Fragoso, n.º 45<br>7080-035 Vendas Novas</p>
                <p><a href="mailto:contacto@dfpinteriores.com">contacto@dfpinteriores.com</a></p>
                <p><a href="tel:+351912026453">+351 912 026 453</a></p>
                <p><small>NIF 504074571</small></p>
            </div>

            <div class="link-group">
                <h3>Métodos de Pagamento</h3>
                <div class="payment-methods">
                    <img src="/img/payments/visa.svg" alt="Visa">
                    <img src="/img/payments/paypal.svg" alt="PayPal">
                    <img src="/img/payments/mastercard.svg" alt="Mastercard">
                    <img src="/img/payments/amex.svg" alt="American Express">
                    <img src="/img/mbway.png" alt="MB Way">
                    <img src="/img/mb.png" alt="Multibanco">
                    <img src="/img/payments/klarna.svg" alt="Klarna">
                    <img src="/img/payments/revolut.svg" alt="Revolut Pay">
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div>
                <p>
                    Preços válidos salvo erro de publicação e limitados ao stock existente.
                    <br>
                    Os preços riscados são os Preços de Venda recomendados pelos fabricantes. Válido apenas na loja Online.
                    <br>
                    Copyright {{ date('Y') }} - DFP Interiores todos os direitos reservados.
                </p>
            </div>
            <div>
                <small>Cofinanciado por</small>
                <div class="cofinanciado-logos">
                    <img src="/img/compete.png" alt="Portugal 2020">
                </div>
            </div>
        </div>
    </div>
</footer>
