import { useEffect, useRef, useState } from 'react';

function VerifyEmail() {
    const [message, setMessage] = useState('Verifying your email...');
    const [success, setSuccess] = useState(false);
    const verificationStarted = useRef(false);

    useEffect(() => {
        async function verifyEmail() {
            const params = new URLSearchParams(window.location.search);
            const token = params.get('token');

            console.log('Verification effect started');
            if (verificationStarted.current) {
                console.log('Duplicate verification prevented');
                return;
            }

            verificationStarted.current = true;
            console.log('Starting verification request');

            if(!token){
                setMessage('The verification link is missing its Token!');
                return;
            }

            try {
                const response = await fetch(
                    'httP://localhost:8000/api/verify.php?token='
                    + encodeURIComponent(token)
                );

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.error ?? 'E-Mail verification failed!'
                    );
                }

                setSuccess(true);
                setMessage(
                    data.message ?? 'E-Mail Verified Successfully!'
                );

            } catch (error) {
                if(error instanceof Error) {
                    setMessage(error.message);
                } else {
                    setMessage('Unable to verify your e-mail!');
                }
            }
        }

        verifyEmail();

    }, []);

    return (

        <main>
            <h1>E-Mail Verification</h1>
            <p role="status">{message}</p>

            {success && (
                <p>You can now return to the Login Panel to Log into your account.</p>
            )}

            <p>
                <a href="/">Return to Login Panel</a>
            </p>

        </main>

    );
}

export default VerifyEmail;