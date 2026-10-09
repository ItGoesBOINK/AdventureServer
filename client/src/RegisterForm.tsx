import { useState } from "react";
import type { FormEvent } from 'react';

type RegisterFormProps = {
    onRegister: (
        email: string,
        username: string,
        password: string
    ) => Promise<void>;
};

function RegisterForm({ onRegister }: RegisterFormProps) {
    const [email, setEmail] = useState('');
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [submitting, setSubmitting] = useState(false);
    const [success, setSuccess] = useState(false);


    async function handleSubmit(event: FormEvent<HTMLFormElement>){
        event.preventDefault();

        setError('');
        setSuccess(false);

        let unmin = 7;
        let unmax = 63;
        let unl = username.length;
        if(unl < unmin || unl > unmax) {
            setError('Username must be between 7 and 63 characters in length!');
            return;
        }

        if(password.length < 8) {
            setError('Password must be at least 8 characters');
            return;
        }

        setSubmitting(true);

        try {
            await onRegister(email, username, password);
            setSuccess(true);
            setPassword('');
        } catch (error) {
            if (error instanceof Error) {
                setError(error.message);
            } else {
                setError("Registration Failed!");
            }
        } finally {
            setSubmitting(false);
        }
    }

    if (success) {
        return (
            <div>
                <h2>Check Your E-Mail!</h2>
                <p>
                    If registration succeeded, you should receive an e-mail
                    with a link to verify your address,
                    and activate your account.
                </p>
            </div>
        );
    }

    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>
                    E-Mail
                    <input
                        type="email"
                        autoComplete="email"
                        value={email}
                        onChange={event => setEmail(event.target.value)}
                        required
                    />
                </label>
            </div>

            <div>
                <label>
                    User Name
                    <input
                        type="text"
                        autoComplete="username"
                        minLength={8}
                        maxLength={64}
                        pattern="[a-zA-Z0-9_.-]{8,64}$"
                        value={username}
                        onChange={event => setUsername(event.target.value)}
                        required
                    />
                </label>
            </div>

            <div>
                <label>
                    Password
                    <input
                        type="password"
                        autoComplete="new-password"
                        minLength={8}
                        maxLength={128}
                        pattern="[a-zA-Z0-9_.-]{8,128}$"
                        value={password}
                        onChange={event => setPassword(event.target.value)}
                        required
                    />
                </label>
            </div>

            <button type="submit" disabled={submitting}>
                {submitting ? 'Registering...' : 'Create Account'}
            </button>

            {error && <p role="alert">{error}</p>}

        </form>
    );
}

export default RegisterForm;