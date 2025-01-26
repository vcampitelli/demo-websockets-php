import { useEffect, useRef, useState } from "preact/hooks";

export default function WriteMessage({ sendMessage }) {
    /**
     * @type {MutableRefObject<HTMLInputElement>}
     */
    const ref = useRef(null);
    const [chatInput, setChatInput] = useState("");

    useEffect(() => {
        if (!ref.current) {
            return;
        }

        ref.current.focus();
    }, []);

    const handleMessageSubmit = (e) => {
        e.preventDefault();
        e.stopPropagation();
        sendMessage(chatInput);
        setChatInput("");
    };

    return (
        <form onSubmit={handleMessageSubmit}>
            {/* biome-ignore lint/a11y/useSemanticElements lint/a11y/noRedundantRoles: O Pico.css precisa do role="group"*/}
            <fieldset role="group">
                <input
                    type="text"
                    placeholder="Escreva sua mensagem aqui"
                    onChange={(e) => setChatInput(e.currentTarget.value)}
                    value={chatInput}
                    ref={ref}
                />
                <button type="submit" className="outline" disabled={chatInput === ""}>
                    Enviar
                </button>
            </fieldset>
        </form>
    );
}
