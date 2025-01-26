import { TYPE_MESSAGE, TYPE_MESSAGE_HISTORY, TYPE_USER_JOINED, TYPE_USER_LEFT } from "./MessageType";

export default function Message({ me, message }) {
    if (message.type === TYPE_MESSAGE) {
        return (
            <MessageInner me={me} message={message} wrapperClass={["message"]}>
                {message.message}
            </MessageInner>
        );
    }

    if (message.type === TYPE_USER_JOINED) {
        return (
            <div className="event-message">
                <MessageUser message={message} /> <span>entrou no chat</span>
            </div>
        );
    }

    if (message.type === TYPE_USER_LEFT) {
        return (
            <div className="event-message">
                <MessageUser message={message} /> <span>saiu do chat</span>
            </div>
        );
    }

    console.error("Mensagem desconhecida", message);
}

function MessageUser({ message }) {
    return (
        <b className="message-username" style={{ color: message.from.color }} title={`#${message.from.id}`}>
            {message.from.username}
        </b>
    );
}

function MessageInner({ me, message, children, wrapperClass = [] }) {
    // wrapperClass.push('message');
    if (me && message.from.username === me.username) {
        wrapperClass.push("is-mine");
    }

    const date = new Date(message.timestamp * 1000);

    return (
        <div key={message.id} className={wrapperClass.join(" ")}>
            <div>
                <MessageUser message={message} /> <span className="contents">{children}</span>
            </div>
            <div className="date">{date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}</div>
        </div>
    );
}
