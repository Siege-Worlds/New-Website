const { ethereum } = window

const metaMaskWeb = {
  networks: {
    ETH: {
      dev: {
        chainId: Web3.utils.toHex(5),
        chainName: 'Goerli',
        nativeCurrency: {
          name: 'GoerliETH',
          symbol: 'GoerliETH',
          decimals: 18,
        },
        rpcUrls: ['https://goerli.infura.io/v3/'],
        blockExplorerUrls: ['https://goerli.etherscan.io'],
        iconUrls: [''],
      },
      prod: {
        chainId: Web3.utils.toHex(1),
        chainName: 'Etherium Mainnet',
        nativeCurrency: {
          name: 'ETH',
          symbol: 'ETH',
          decimals: 18,
        },
        rpcUrls: ['https://mainnet.infura.io/v3/'],
        blockExplorerUrls: ['https://etherscan.io/'],
        iconUrls: [''],
      },
    },
    MATIC: {
      dev: {
        chainId: Web3.utils.toHex(80001),
        chainName: 'POLYGON Mumbai',
        nativeCurrency: {
          name: 'MATIC',
          symbol: 'MATIC',
          decimals: 18,
        },
        rpcUrls: ['https://rpc-mumbai.maticvigil.com/'],
        blockExplorerUrls: ['https://mumbai.polygonscan.com/'],
        iconUrls: [''],
      },
      prod: {
        chainId: Web3.utils.toHex(137),
        chainName: 'POLYGON',
        nativeCurrency: {
          name: 'MATIC',
          symbol: 'MATIC',
          decimals: 18,
        },
        rpcUrls: ['https://polygon-rpc.com'],
        blockExplorerUrls: ['https://polygonscan.com/'],
        iconUrls: [''],
      },
    },
  },
  erc20Abi: [
    {
      constant: true,
      inputs: [{ name: 'account', type: 'address' }],
      name: 'balanceOf',
      outputs: [{ name: '', type: 'uint256' }],
      payable: false,
      stateMutability: 'view',
      type: 'function',
    },
    {
      constant: false,
      inputs: [
        { name: '_to', type: 'address' },
        { name: '_value', type: 'uint256' },
      ],
      name: 'transfer',
      outputs: [{ name: '', type: 'bool' }],
      payable: false,
      stateMutability: 'nonpayable',
      type: 'function',
    },
    {
      constant: true,
      inputs: [],
      name: 'decimals',
      outputs: [{ name: '', type: 'uint8' }],
      payable: false,
      stateMutability: 'view',
      type: 'function',
    },
  ],
  coins: {
    ETH: 'ETH',
    MATIC: 'MATIC',
    USDC: 'ETH',
    USDT: 'ETH',
    EDIVI: 'ETH',
  },
  erc20: {
    USDC: { address: '0xa0b86991c6218b36c1d19d4a2e9eb0ce3606eb48', decimal: 6 },
    USDT: { address: '0xdAC17F958D2ee523a2206206994597C13D831ec7', decimal: 6 },
    EDIVI: {
      address: '0x246908BfF0b1ba6ECaDCF57fb94F6AE2FcD43a77',
      decimal: 8,
    },
  },
  isMobileDevice: (_) =>
    !!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
      navigator.userAgent
    ),
  platform: null,
  provider: null,
  loginText: null,
  metaMaskProvider: null,
  coinbaseWalletProvider: null,
  type: null,
  network: null,
  networkType: null,
  address: null,
  loginHash: null,
  load: (isTestnet, askToInstall, type, loginText) => {
    metaMaskWeb.platform = isTestnet ? 'dev' : 'prod'
    metaMaskWeb.type = type
    metaMaskWeb.loginText = loginText
    if (typeof Web3 == 'undefined') {
      if (askToInstall) {
        if (confirm('Would you like to install MetaMask!')) {
          window.open('https://metamask.app.link/dapp/')
        }
      }
      return
    }
    if (window.ethereum && window.ethereum.providers) {
      for (let value of window.ethereum.providers) {
        if (value.isMetaMask) {
          metaMaskWeb.metaMaskProvider = value
        } else if (value.isCoinbaseWallet) {
          metaMaskWeb.coinbaseWalletProvider = value
        }
      }
    } else if (window.ethereum && window.ethereum.isMetaMask) {
      metaMaskWeb.metaMaskProvider = window.ethereum
    } else if (window.ethereum && window.ethereum.isCoinbaseWallet) {
      metaMaskWeb.coinbaseWalletProvider = window.ethereum
    }
    if (metaMaskWeb.type == 'metamask' && metaMaskWeb.metaMaskProvider) {
      metaMaskWeb.provider = metaMaskWeb.metaMaskProvider
    } else if (
      metaMaskWeb.type == 'coinbase' &&
      metaMaskWeb.coinbaseWalletProvider
    ) {
      metaMaskWeb.provider = metaMaskWeb.coinbaseWalletProvider
    }
    if (metaMaskWeb.provider && askToInstall) {
      metaMaskWeb.provider.on('chainChanged', metaMaskWeb.setSelectedNetwork)
      metaMaskWeb.provider.on('accountsChanged', metaMaskWeb.setAccount)
      if (metaMaskWeb.isMobileDevice()) {
        alert('Only open if not already done\n' + document.location.href)
        switch (metaMaskManager.type) {
          case 'metamask':
            document.location.href =
              'https://metamask.app.link/dapp/' + document.location.href
            break
          case 'coinbase':
            document.location.href =
              'https://go.cb-w.com/dapp?cb_url=' + document.location.href
            break
          /*case 'walletconnect':
          document.location.href = ('https://example.wallet/wc?uri=wc:00e46b69-d0cc-4b3e-b6a2-cee442f97188@1' + document.location.href);
          break;*/
        }
        return
      }
    }
  },
  setSelectedNetwork: (networkID) => {
    console.log('setNetwork', networkID)
    switch (networkID) {
      case 1:
        metaMaskWeb.network = 'Mainnet'
        metaMaskWeb.networkType = 'ETH'
        break
      case 5:
        metaMaskWeb.network = 'Goerli'
        metaMaskWeb.networkType = 'ETH'
        break
      case 137:
        metaMaskWeb.network = 'Polygon'
        metaMaskWeb.networkType = 'MATIC'
        break
      case 80001:
        metaMaskWeb.network = 'Mumbai'
        metaMaskWeb.networkType = 'MATIC'
        break
    }
    metaMaskWeb.setNetwork()
  },
  setNetwork: async (network) => {
    if (network != metaMaskWeb.networkType) {
      try {
        await metaMaskWeb.provider.request({
          method: 'wallet_switchEthereumChain',
          params: [
            {
              chainId:
                metaMaskWeb.networks[network.toUpperCase()][
                  metaMaskWeb.platform
                ].chainId,
            },
          ],
        })
      } catch (error) {
        if (error.code === 4902) {
          await metaMaskWeb.addNetwork(network)
        }
      }
      await sleep(2000)
    } else {
      metaMaskWeb.setAccount()
    }
  },
  addNetwork: async (network) => {
    try {
      await metaMaskWeb.provider.request({
        method: 'wallet_addEthereumChain',
        params: [
          metaMaskWeb.networks[network.toUpperCase()][metaMaskWeb.platform],
        ],
      })
      await sleep(2000)
      await metaMaskWeb.setNetwork(network)
    } catch (addError) {
      console.log('Failed to add network: ' + network, addError)
    }
  },
  setAccount: async (accounts) => {
    try {
      if (!accounts) {
        accounts = await metaMaskWeb.provider.request({
          method: 'eth_requestAccounts',
        })
      }
    } catch (error) {
      console.error('eth_requestAccounts error', error)
    }
    if (accounts && Array.isArray(accounts) && accounts.length > 0) {
      metaMaskWeb.address = accounts[0]
      await metaMaskWeb.getLoginHash()
    }
  },
  signMessage: async (message) => {
    if (metaMaskWeb.provider && metaMaskWeb.address) {
      web3 = new Web3(metaMaskWeb.provider)
      return await web3.eth.personal.sign(message, metaMaskWeb.address)
    }
  },
  getLoginHash: async (_) => {
    if (!metaMaskWeb.loginHash) {
      web3 = new Web3(metaMaskWeb.provider)
      metaMaskWeb.loginHash = await metaMaskWeb.signMessage(
        metaMaskWeb.loginText
      )
    }
    if (!metaMaskWeb.loginHash) {
      metaMaskWeb.address = null
    }
  },
}
