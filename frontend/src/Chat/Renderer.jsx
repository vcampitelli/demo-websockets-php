import { useEffect, useRef } from "preact/hooks";

import Message from "./Message";
import WriteMessage from "./WriteMessage";

export default function ChatRenderer({ username, messages, sendMessage }) {
    const ref = useRef(null);

    // Fazendo scroll para o fim da lista
    // biome-ignore lint/correctness/useExhaustiveDependencies: Queremos fazer scroll ao receber uma nova mensagem
    useEffect(() => {
        if (!ref.current) {
            return null;
        }
        // @TODO verificar se o usuário fez scroll manual para evitar que ele perca onde está
        ref.current.scrollIntoView({ behavior: "smooth" });
    }, [messages]);

    // Enviando mensagem
    const sendMessageWrapper = (message) => {
        const json = { type: "message", message: message };
        console.log("Enviando mensagem", json);
        sendMessage(json);
    };

    return (
        <div id="chat">
            <article>
                {messages.map((message) => (
                    <Message key={`message-${message.id}`} me={{ username }} message={message} />
                ))}
                <div className="phantom" ref={ref} />
            </article>

            <WriteMessage sendMessage={sendMessageWrapper} />
        </div>
    );
}
