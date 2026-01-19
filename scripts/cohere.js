// curl --request POST \
//   --url https://api.cohere.com/v2/chat \
//   --header 'accept: application/json' \
//   --header 'content-type: application/json' \
//   --header "Authorization: bearer ${TOKEN}" \
//   --data '{
//     "model": "command-a-03-2025",
//     "messages": [
//       {
//         "role": "user",
//         "content": "Tell me about LLMs"
//       }
//     ]
//   }'

async function cohere_ask(message, lambda, fail_lambda) {
    // fetch env only when needed
    // dont store it as a variable
    await fetch('../.env')
        .then((e) => e.json())
        .then(async (e) => {
            let token = e['cohere_key'];

            await fetch('https://api.cohere.com/v2/chat',
                {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `bearer ${token}`
                    },
                    body: JSON.stringify({
                        "model": "command-a-03-2025",
                        "messages": [
                            {
                                "role": "user",
                                "content": message
                            }
                        ]
                    })
                }
            )
                .then((e) => e.json())
                .then((e) => {
                    let r = e['message']['content'][0]['text'];
                    console.log(r);
                    lambda(r)
                    // yield r;
                    // is this c# async syntax lol
                })
                .catch((_) => fail_lambda())

        })
        .catch((_) => fail_lambda())

}
