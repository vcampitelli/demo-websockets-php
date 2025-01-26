import { ReadyState } from "react-use-websocket";
import Header from "../Header";

export default function ChatLoading({ state }) {
    const connectionStatus = {
        [ReadyState.CONNECTING]: "Conectando",
        [ReadyState.CLOSING]: "Fechando",
        [ReadyState.CLOSED]: "Fechado",
        [ReadyState.UNINSTANTIATED]: "Aguardando",
    }[state];
    return (
        <div className="container-small">
            <Header />
            <article>
                <span aria-busy="true" className={state === ReadyState.CLOSED ? "is-closed" : ""}>
                    Status da Conexão: {connectionStatus || "Desconhecido"}
                </span>
            </article>
        </div>
    );
}
