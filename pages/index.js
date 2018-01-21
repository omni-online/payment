/*
 Build with the following repo: https://github.com/ruanyf/react-babel-webpack-boilerplate
*/
import StripeCheckout from 'react-stripe-checkout'
import Head from 'next/head'
import React from 'react'

const noop = () => {}

const getParameterByName = (name) => {
  if (typeof window === 'undefined') {
    return ''
  }
  const url = window.location.href
  const regex = new RegExp(`[?&]${name}(=([^&#]*)|&|#|$)`)
  const results = regex.exec(url)

  if (!results) {
    return null
  }

  if (!results[2]) {
    return ''
  }

  return decodeURIComponent(results[2].replace(/\+/g, ' '))
}

export default class extends React.Component {
  componentDidMount () {
    this.amount = parseInt(getParameterByName('p')) || 100000
  }

  render () {
    return (
      <main>
        <Head>
          <title>Omni Online LLC</title>
        </Head>
        <h1>Omni Online LLC</h1>
        <h3>Payment Processing Form</h3>

        <p>Use the button below to begin processing a payment. Be sure to include your contact email so we can match it with the invoice</p>

        {process.env.NODE_ENV === 'development' && (<aside>STRIPE_KEY: {process.env.STRIPE_KEY}</aside>)}

        <StripeCheckout
          name='Omni Online LLC'
          token={noop}
          amount={this.amount}
          panelLabel='Pay'
          currency='USD'
          locale='en'
          stripeKey={process.env.STRIPE_KEY}>
          <button className='btn btn-success text-uppercase px-4 py-3'>
            Pay {`$${parseFloat(this.amount / 100).toFixed(2)}`} Now
          </button>
          <style jsx>{`
            button {
              position: relative;
              border-radius: 4px;
              background-color: #3ea8e5;
              background-image: linear-gradient(-180deg,#44b1e8,#3098de);
              box-shadow: 0 1px 0 0 rgba(46,86,153,.15), inset 0 1px 0 0 rgba(46,86,153,.1), inset 0 -1px 0 0 rgba(46,86,153,.4);
              font-size: 17px;
              line-height: 21px;
              height: 37px;
              font-weight: 700;
              text-shadow: 0 -1px 0 rgba(0,0,0,.12);
              color: #fff;
              cursor: pointer;
              transition: all .2s ease-in-out;
            }

            button:hover {
              background-image: linear-gradient(180deg,#328ac3,#277bbe);
            }

            aside {
              background-color: grey;
              padding: 1em;
            }
          `}</style>
        </StripeCheckout>
      </main>
    )
  }
}
