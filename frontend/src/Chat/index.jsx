import { useEffect, useState } from "preact/hooks";
import useWebSocket, { ReadyState } from "react-use-websocket";
import ChatLoading from "./Loading";
import { TYPE_MESSAGE, TYPE_MESSAGE_HISTORY, TYPE_USER_JOINED, TYPE_USER_LEFT } from "./MessageType";
import ChatRenderer from "./Renderer";

export default function Chat({ username }) {
    const [messages, setMessages] = useState([]);

    // Abrindo conexão com o WebSocket
    const { sendJsonMessage, lastJsonMessage, readyState } = useWebSocket("ws://localhost:8000", {
        onOpen: () => {
            console.log("Aberto WebSocket");

            const message = { type: "join", username: username };
            console.log("Enviando mensagem", message);
            sendJsonMessage(message);
        },
        onError: (err) => {
            console.error(err);
            alert("Um erro ocorreu ao abrir o WebSocket. Por favor, verifique o console.");
        },
    });

    // biome-ignore lint/correctness/useExhaustiveDependencies: Estamos usando o messages para concatenar o array
    useEffect(() => {
        if (lastJsonMessage === null) {
            return;
        }

        console.log("Mensagem recebida", lastJsonMessage);
        switch (lastJsonMessage.type) {
            case TYPE_MESSAGE:
            case TYPE_USER_JOINED:
            case TYPE_USER_LEFT:
                setMessages([...messages, lastJsonMessage]);
                break;

            case TYPE_MESSAGE_HISTORY:
                setMessages([...messages, ...lastJsonMessage.messages]);
                break;
        }
    }, [lastJsonMessage]);

    // Aguardando a conexão
    if (readyState !== ReadyState.OPEN) {
        return <ChatLoading state={readyState} />;
    }

    return <ChatRenderer username={username} messages={messages} sendMessage={sendJsonMessage} />;
}
