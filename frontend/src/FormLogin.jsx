import { useState } from "preact/hooks";
import Header from "./Header";

export default function FormLogin({ onSubmit }) {
    const [username, setUsername] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();
        e.stopPropagation();
        onSubmit(username);
    };

    return (
        <form onSubmit={handleSubmit} className="container-small">
            <Header />
            <label>
                <input
                    type="text"
                    placeholder="Para começar, me diga seu nome"
                    onChange={(e) => setUsername(e.currentTarget.value.replaceAll(/\s+/g, " "))}
                    value={username}
                    autoFocus
                    required
                />
            </label>
            <button type="submit" disabled={username === ""}>
                Entrar
            </button>
        </form>
    );
}
